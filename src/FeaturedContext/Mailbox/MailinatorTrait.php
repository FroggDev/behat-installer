<?php
namespace froggdev\BehatInstaller\FeaturedContext\Mailbox;

trait MailinatorTrait
{
    /**
     * @Given Je me connecte sur Mailinator avec le compte ":email"
     *
     * @param string $email
     */
    public function iLogOnMailinator($email): void
    {
        // replace uservar in param
        $email = $this->replaceUserVar($email);

        $this->iGoOn('https://www.mailinator.com');

        $this->iFillWith('#inboxfield', $email);

        $this->iClickSelector('BUTTON.btn.btn-dark');
    }

    /**
     * @Given Dans Mailinator je click sur le mail ":titre"
     *
     * @param string $email
     */
    public function iOpenMsgMailinator($title): void
    {
        // replace uservar in param
        $title = $this->replaceUserVar($title);

        $this->iClickOnTheText($title);
    }

    /**
     * @Given Dans Mailinator je me place sur le contenu du message
     */
    public function iFocusMessageFrameMailinator(): void
    {
        $this->switchMainFrame();

        $this->switchFrame('msg_body');

        $this->iWaitSec(1);
    }

    /**
     * @Given Dans Mailinator j'efface les mails
     *
     * @param string $email
     */
    public function iCleanMailinator(): void
    {
        $this->switchMainFrame();

        $this->iClickSelector('#sidebar-menu UL LI:nth-child(1)');

        $this->iWaitSec(1);

        $checks = $this->getSession()->getPage()->findAll('css','#inboxpane INPUT');

        foreach($checks as $check){
            $check->click();
        }

        $this->iClickSelector('#trash_but');

        // A TESTER =>

        $this->iWaitSec(1);

        $this->iShouldSee('message deleted');

        // ALTERNATIVE

        /*
        $checks = $this->getSession()->getPage()->findAll('css','#inboxpane INPUT');

        if(count($checks)>0){

        }
        */
    }
}

