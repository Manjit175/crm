<?php

namespace OroCRM\Bundle\CampaignBundle\Command;

use OroCRM\Bundle\CampaignBundle\Entity\Repository\EmailCampaignRepository;
use OroCRM\Bundle\CampaignBundle\Model\EmailCampaignSender;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Oro\Bundle\CronBundle\Command\CronCommandInterface;
use OroCRM\Bundle\CampaignBundle\Entity\EmailCampaign;

/**
 * Command to send scheduled email campaigns
 */
class SendEmailCampaignsCommand extends ContainerAwareCommand implements CronCommandInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDefaultDefinition()
    {
        return '*/1 * * * *';
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('oro:cron:send-email-campaigns')
            ->setDescription('Send email campaigns');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $emailCampaigns = $this->getEmailCampaignRepository()->findEmailCampaignsToSend();

        if (!$emailCampaigns) {
            $output->writeln('<info>No email campaigns to send</info>');
            return;
        }

        $output->writeln(
            sprintf('<comment>Email campaigns to send:</comment> %d', count($emailCampaigns))
        );

        $this->send($output, $emailCampaigns);
        $output->writeln(sprintf('<info>Finished email campaigns sending</info>'));
    }

    /**
     * Send email campaigns
     *
     * @param OutputInterface $output
     * @param EmailCampaign[] $emailCampaigns
     */
    protected function send($output, array $emailCampaigns)
    {
        $sender = $this->getSender();

        foreach ($emailCampaigns as $emailCampaign) {
            $output->writeln(sprintf('<info>Sending email campaign</info>: %s', $emailCampaign->getName()));
            $sender->send($emailCampaign);
        }
    }

    /**
     * @return EmailCampaignSender
     */
    protected function getSender()
    {
        $emailTransport = $this->getContainer()->get('orocrm_campaign.transport.email');
        $sender = $this->getContainer()->get('orocrm_campaign.email_campaign.sender');
        $sender->setTransport($emailTransport);

        return $sender;
    }

    /**
     * @return EmailCampaignRepository
     */
    protected function getEmailCampaignRepository()
    {
        return $this->getContainer()->get('doctrine')->getRepository('OroCRMCampaignBundle:EmailCampaign');
    }
}
