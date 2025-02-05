<?php

namespace App\Controllers;

use App\Repository\MedecinRepository;

class MedecinController
{
    private $medecinRepository;

    public function __construct()
    {
        global $db;
        $this->medecinRepository = new MedecinRepository($db);
    }

    public function index()
    {
        $medecins = $this->medecinRepository->findAll();
        require_once __DIR__ . '/../Views/medecins/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'specialite' => $_POST['specialite'] ?? ''
            ];

            if ($this->medecinRepository->create($data)) {
                header('Location: /medecins');
                exit;
            }
        }

        require_once __DIR__ . '/../Views/medecins/create.php';
    }

    public function edit($id)
    {
        $medecin = $this->medecinRepository->findById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'specialite' => $_POST['specialite'] ?? ''
            ];

            if ($this->medecinRepository->update($id, $data)) {
                header('Location: /medecins');
                exit;
            }
        }

        require_once __DIR__ . '/../Views/medecins/edit.php';
    }

    public function delete($id)
    {
        if ($this->medecinRepository->delete($id)) {
            header('Location: /medecins');
            exit;
        }
    }

    public function disponibilites($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? date('Y-m-d');
            $slots = $this->medecinRepository->findAvailableSlots($id, $date);
            echo json_encode($slots);
            exit;
        }
    }
}
