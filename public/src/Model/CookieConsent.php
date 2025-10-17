<?php
class CookieConsent
{
    public $consentCookie = 'cookie_consent';
    public $cookies = []; // tableau des cookies définis par catégorie

    public function __construct()
    {
        // Charger le consentement existant
        $this->loadConsent();
    }

    private function loadConsent()
    {
        if (isset($_COOKIE[$this->consentCookie])) {
            $this->cookies = json_decode($_COOKIE[$this->consentCookie], true) ?? [];
        }
    }

    // Définir un cookie selon le consentement
    public function setCookie($name, $value, $expire = 3600 * 24 * 30, $path = "/", $category = 'necessary')
    {
        if ($category === 'necessary' || $this->isCategoryAccepted($category)) {
            setcookie($name, $value, time() + $expire, $path);
            $_COOKIE[$name] = $value;
        }
    }

    // Vérifier si une catégorie est acceptée
    public function isCategoryAccepted($category)
    {
        return $this->cookies[$category] ?? false;
    }

    // Sauvegarder le consentement
    public function saveConsent(array $consent)
    {
        // $consent = ['analytics' => true, 'marketing' => false, ...]
        $this->cookies = $consent;
        setcookie($this->consentCookie, json_encode($this->cookies), time() + 3600 * 24 * 365, "/");
        $_COOKIE[$this->consentCookie] = json_encode($this->cookies);
    }

    // Supprimer un cookie
    public function deleteCookie($name)
    {
        setcookie($name, "", time() - 3600, "/");
        unset($_COOKIE[$name]);
    }

    // Supprimer tous les cookies d’une catégorie
    public function deleteCategoryCookies($category, array $cookieNames)
    {
        if (!$this->isCategoryAccepted($category)) {
            foreach ($cookieNames as $name) {
                $this->deleteCookie($name);
            }
        }
    }
}
?>