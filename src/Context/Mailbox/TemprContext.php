<?php
namespace froggdev\BehatContexts\Context\Mailbox;

/**
 * Trait TemprContext
 * @package froggdev\BehatContexts\Context\Mailbox
 */
trait TemprContext
{
    /**
     * @Given Je me connecte sur Tempr.email avec le compte ":email"
     *
     * @param string $email
     */
    public function iLogOnTempr($email): void
    {
        // replace uservar in param
        $email = $this->replaceUserVar($email);

        $this->iGoOn('https://tempr.email/en/');

        $emailPart = explode('@',$email);

        $this->iFillWith('#LoginLocalPart', $emailPart[0]);

        $this->iClickSelector('input[name=LoginButton]');

        $this->iWaitSec(3);
    }

    /**
     * @Given Dans Tempr.email je click sur le mail ":titre"
     *
     * @param string $email
     */
    public function iOpenMsgTempr($title): void
    {
        // replace uservar in param
        $title = $this->replaceUserVar($title);

        $this->iClickOnTheText($title);
    }

    /**
     * @Given Dans Tempr.email je me place sur le contenu du message
     */
    public function iFocusMessageFrameTempr(): void
    {
        $this->switchMainFrame();

        $this->switchFrame('iframeMessage');

        $this->iWaitSec(1);
    }

    /**
     * @Given Dans Tempr.email j'efface les mails
     *
     * @param string $email
     */
    public function iCleanTempr(): void
    {

        $this->switchMainFrame();

        $this->getElementFromText('Back')->click();

        $this->iWaitSec(3);

        $this->iClickOnTheText('All');

        $this->iClickSelector('input[name=RemoveMoreButton]');

        $this->iClickOnTheAlertWindow();

        $this->iWaitSec(1);

        $this->iShouldSee('E-mail(s) were successfully deleted');
    }
}
