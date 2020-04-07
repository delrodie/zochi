<?php

namespace App\Command;

use App\Entity\Domaine;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateDomaineCommand extends Command
{
    protected static $defaultName = 'CreateDomaine';

    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:domaine:create')
            ->setDescription('Creer un nouveau domaine')
            ->setDefinition([
                new InputArgument('libelle', InputArgument::REQUIRED, 'Le nom du domaine')
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domaine = new Domaine();
        $slugify = new Slugify();

        $libelle = $input->getArgument('libelle');
        $slug = $slugify->slugify($libelle);

        $domaine->setLibelle($libelle);
        $domaine->setSlug($slug);

        $errors = $this->validator->validate($domaine);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->persist($domaine);
        $this->em->flush();

        $output->writeln(sprintf('<fg=green>Le domaine a été crée avec succès!'));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('libelle')){
            $question = new Question('Entrez le nom du domaine : ');
            $questions['libelle'] = $question;
        }

        foreach ($questions as $name => $question){
            $awser =$this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $awser);
        }
    }
}
