<?php

namespace App\Models;

class Patient
{
    private int $id;
    private int $user_id;
    private string $numero_securite_sociale;
    private string $date_naissance;
    private ?string $nom;
    private ?string $prenom;
    private ?string $email;
    private ?string $telephone;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getNumeroSecuriteSociale(): string
    {
        return $this->numero_securite_sociale;
    }

    public function getDateNaissance(): string
    {
        return $this->date_naissance;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'numero_securite_sociale' => $this->numero_securite_sociale,
            'date_naissance' => $this->date_naissance,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone
        ];
    }
}
