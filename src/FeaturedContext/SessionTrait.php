<?php
namespace froggdev\BehatContexts\FeaturedContext;

trait SessionTrait
{
    #########################
    # ACTION SESSION/COOKIE #
    #########################

    /**
     * @Given Le cookie ":nom_du_cookie" devrait être rempli avec la valeur "valeur_du_cookie"
     *
     * @param string $name
     * @param string $expectedValue
     * @throws \Exception
     */
    public function cookieShouldBe(string $name, string $expectedValue): void
    {
        // value in cookie
        $value = $this->getSession()->getCookie($name);

        if ($expectedValue!==$value) {
            throw new \Exception("cookie $name should be fill with $expectedValue but value is " . $value);
        }
    }

    /**
     * @Given J'efface la session utilisateur
     */
    public function sessionReset(): void
    {
        $this->getSession()->reset();
    }
}