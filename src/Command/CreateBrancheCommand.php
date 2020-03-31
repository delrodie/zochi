<?php


namespace App\Command;


use App\Entity\Branche;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateBrancheCommand extends Command
{
    private $em;
    private $validator;
    private $slugger;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SluggerInterface $slugger)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->slugger = $slugger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:branche:create')
            ->setDescription('Creer une nouvelle branche')
            ->setDefinition([
                new InputArgument('nom', InputArgument::REQUIRED, 'Le nom de la branche')
            ])
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $branche = new Branche();

        $nom = $input->getArgument('nom');
        $slug = $this->slugger->slug($nom);

        $branche->setNom($nom);
        $branche->setSlug($slug);

        $errors = $this->validator->validate($branche);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->persist($branche);
        $this->em->flush();

        $output->writeln(sprintf('<fg=green>La branche a été créée avec succès!'));

    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('nom')){
            $question = new Question('Entrez le nom de la branche : ');
            $questions['nom'] = $question;
        }

        foreach ($questions as $name => $question){
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}