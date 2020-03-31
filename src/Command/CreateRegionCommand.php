<?php


namespace App\Command;


use App\Entity\Branche;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRegionCommand extends Command
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
            ->setName('app:region:create')
            ->setDescription('Creer une nouvelle region')
            ->setDefinition([
                new InputArgument('nom', InputArgument::REQUIRED, 'Le nom de la region')
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $region = new Region();

        $nom = $input->getArgument('nom');
        $slug = $this->slugger->slug($nom);

        $region->setNom($nom);
        $region->setSlug($slug);

        $errors = $this->validator->validate($region);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->persist($region);
        $this->em->flush();

        $output->writeln(sprintf('<fg=green>La région a été créée avec succès!'));

    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('nom')){
            $question = new Question('Entrez le nom de la région : ');
            $questions['nom'] = $question;
        }

        foreach ($questions as $name => $question){
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}