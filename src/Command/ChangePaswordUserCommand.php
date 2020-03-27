<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChangePaswordUserCommand extends Command
{
    private $em;
    private $validator;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:user:change-password')
            ->setDescription('Modification du mot de passe')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'Nom utilisateur'),
                new InputArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $user = $this->em->getRepository(User::class)->findOneByUsername($username);
        $password = $input->getArgument('password');
        $password = $this->passwordEncoder->encodePassword($user, $password);

        $user->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0){
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->em->flush();

        $output->writeln(sprintf('<fg=green>Modification du mot de passe de l\'utilisateur %s', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')){
            $question = new Question('Le nom de l\'utilisateur concerné: ');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('password')){
            $question = new Question('Le nouveau mot de passe : ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question){
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}