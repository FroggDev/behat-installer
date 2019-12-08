<?php
namespace froggdev\BehatInstaller\FeaturedContext\Mailbox;

/**
 * Trait YopmailTrait
 * @package froggdev\BehatInstaller\FeaturedContext\Mailbox
 */
trait YopmailTrait
{
    /**
     * @Given Je me connecte sur Yopmail avec le compte ":email"
     *
     * @param string $email
     */
    public function iLogOnYopmail($email): void
    {
        // replace uservar in param
        $email = $this->replaceUserVar($email);

        $this->iGoOn('http://www.yopmail.com');

        $this->iFillWith('#login', $email);

        $this->iClickSelector('INPUT.sbut');
    }

    /**
     * @Given Dans Yopmail je click sur le mail ":titre"
     *
     * @param string $email
     */
    public function iOpenMsgYopmail($title): void
    {
        // replace uservar in param
        $title = $this->replaceUserVar($title);

        $this->iFocusMessageListFrameYopmail();

        $this->iClickOnTheText($title);
    }

    /**
     * @Given Dans Yopmail j'efface les mails
     *
     * @param string $email
     */
    public function iCleanYopmail(): void
    {

        $this->iFocusMessageListFrameYopmail();

        $this->iClickSelector('A.igif.lmenudelall');

        $this->iClickSelector('A.igif.lmen_all');

        $this->iShouldSee('Aucun message pour');
    }

    /**
     * @Given Dans Yopmail je me place sur le contenu du message
     */
    public function iFocusMessageFrameYopmail(): void
    {
        $this->switchMainFrame();

        $this->switchFrame('ifmail');
    }

    /**
     * @Given Dans Yopmail je me place sur la liste des mails
     */
    public function iFocusMessageListFrameYopmail(): void
    {
        $this->switchMainFrame();

        $this->switchFrame('ifinbox');
    }
}