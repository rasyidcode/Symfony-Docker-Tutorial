<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\UuidV1;

#[AsCommand(
    name: 'app:bootstrap',
    description: 'Boostrap the application database',
)]
class BootstrapCommand extends Command
{

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'User email address', 'me@test.com')
            ->addOption('password', null, InputOption::VALUE_REQUIRED, 'User password', 'password')
            ->addOption('redirect-uris', null, InputOption::VALUE_REQUIRED, 'Redirect uris', 'http://oauth2-client-sample.test:8080/callback');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email');
        $password = $input->getOption('password');

        $clientName = 'Test Client';
        $clientId = 'testclientid';
        $clientSecret = 'testpass';
        $clientDesc = 'Test client App';
        $scopes = ['profile', 'email', 'blog_read'];
        $grantTypes = ['authorization_code', 'refresh_token'];
        $redirectUris = explode(',', $input->getOption('redirect-uris'));

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setUuid(new UuidV1());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $conn = $this->entityManager->getConnection();
        $conn->insert('oauth2_client', [
            'identifier' => $clientId,
            'secret' => $clientSecret,
            'name' => $clientName,
            'redirect_uris' => implode(' ', $redirectUris),
            'grants' => implode(' ', $grantTypes),
            'scopes' => implode(' ', $scopes),
            'active' => 1,
            'allow_plain_text_pkce' => 0
        ]);

        $conn->insert('oauth2_client_profile', [
            'id' => 1,
            'client_id' => $clientId,
            'name' => $clientName,
            'description' => $clientDesc
        ]);

        $io->success('Bootstrap completed.');

        return Command::SUCCESS;
    }
}
