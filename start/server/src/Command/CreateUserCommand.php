<?php

namespace App\Command;

use App\Entity\User as UserEntity;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
	protected static $defaultName = 'app:create-user';

	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $entityManager;
	/** @var \Symfony\Component\Validator\Validator\ValidatorInterface */
	private $validator;
	/** @var \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface */
	private $passwordEncoder;

	public function __construct(
		EntityManagerInterface $entityManager,
		ValidatorInterface $validator,
		EncoderFactoryInterface $encoderFactory
	) {
		parent::__construct();
		$this->entityManager = $entityManager;
		$this->validator = $validator;
		$this->passwordEncoder = $encoderFactory->getEncoder(UserEntity::class);
	}

	protected function configure()
	{
		$this
			->setDescription('Create an application user in the database.')
			->addArgument('email', InputArgument::REQUIRED, 'User Email')
			->addArgument('password', InputArgument::OPTIONAL, 'User Password')
			->addOption('editor', null, InputOption::VALUE_NONE, 'Should user have editor role?')
			->addOption('admin', null, InputOption::VALUE_NONE, 'Should user have admin role?')
			->setHelp(<<<EOF
The <info>%command.name%</info> command creates a new application user inside
the configured database. This command assumes said database already exists.

If you do not supply a password as the second (optional) argument - which is
recommended not to do so to prevent password being read from your shell command
history - the command will interactively prompt you to enter one. If this
command is run non-interactively and no password is supplied, an error will be
thrown.

Additionally, Admin and Editor roles can be assigned to the new user with the
following flags:

  <comment>--admin</comment>  allow a user to create, edit and delete themselves and other users.
           Admins automatically inherit Editor privileges.
  <comment>--editor</comment> allow a user to create, edit and delete webhook endpoints.

EOF
			)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$errorIo = $output instanceof ConsoleOutputInterface ? new SymfonyStyle($input, $output->getErrorOutput()) : $io;

		$email = $input->getArgument('email');
		$violations = $this->validator->validate($email, [
			new Assert\NotBlank,
			new Assert\Email,
		]);
		if (count($violations) > 0) {
			$errorIo->error('Email is not valid.');
			return 1;
		}

		$password = $input->getArgument('password');
		if (empty($password)) {
			if ($input->isInteractive()) {
				$passwordQuestion = $this->buildPasswordQuestion();
				$password = $errorIo->askQuestion($passwordQuestion);
			} else {
				$errorIo->error('Password not supplied; cannot prompt for input in non-interactive mode.');
				return 1;
			}
		}

		$encodedHash = $this->passwordEncoder->encodePassword($password, null);

		$roles = [];
		if ($input->getOption('admin')) {
			$roles[] = 'ROLE_ADMIN';
		}
		if ($input->getOption('editor')) {
			$roles[] = 'ROLE_EDITOR';
		}

		$user = new UserEntity($email, $encodedHash, $roles);
		try {
			$this->entityManager->persist($user);
			$this->entityManager->flush();
		} catch (UniqueConstraintViolationException $e) {
			$errorIo->error('A user with that email address already exists.');
		}

		$io->writeln(sprintf(
			'Successfully created the user <info>%s</info> with ID <info>%d</info>.',
			$email,
			$user->getId()
		));
		return 0;
	}

	private function buildPasswordQuestion()
	{
		return (new Question('Type in password for new user'))
			->setHidden(true)
			->setMaxAttempts(5)
			->setValidator(function ($value) {
				if ('' === trim($value)) {
					throw new InvalidArgumentException('The password must not be empty.');
				}
				if (strlen($value) < UserEntity::MIN_PASSWORD_LENGTH) {
					throw new InvalidArgumentException(sprintf(
						'Password is too short, must contain at least %d characters.',
						UserEntity::MIN_PASSWORD_LENGTH
					));
				}
				return $value;
			});
	}
}
