<?php

namespace App\Controllers;

use App\Repository\RendezVousRepository;
use App\Repository\PatientRepository;
use App\Repository\MedecinRepository;

class RendezVousController
{
    private $rendezVousRepository;
    private $patientRepository;
    private $medecinRepository;

    public function __construct()
    {
        global $db;
        $this->rendezVousRepository = new RendezVousRepository($db);
        $this->patientRepository = new PatientRepository($db);
        $this->medecinRepository = new MedecinRepository($db);
    }

    public function index()
    {
        $rendezVous = $this->rendezVousRepository->findAll();
        require_once __DIR__ . '/../Views/rendez-vous/index.php';
    }

    public function create()
    {
        $patients = $this->patientRepository->findAll();
        $medecins = $this->medecinRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => $_POST['patient_id'] ?? '',
                'medecin_id' => $_POST['medecin_id'] ?? '',
                'date' => $_POST['date'] ?? '',
                'heure' => $_POST['heure'] ?? '',
                'motif' => $_POST['motif'] ?? '',
                'statut' => 'En attente'
            ];

            if ($this->rendezVousRepository->create($data)) {
                header('Location: /rendez-vous');
                exit;
            }
        }

        require_once __DIR__ . '/../Views/rendez-vous/create.php';
    }

    public function edit($id)
    {
        $rendezVous = $this->rendezVousRepository->findById($id);
        $patients = $this->patientRepository->findAll();
        $medecins = $this->medecinRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'patient_id' => $_POST['patient_id'] ?? $rendezVous['patient_id'],
                'medecin_id' => $_POST['medecin_id'] ?? $rendezVous['medecin_id'],
                'date' => $_POST['date'] ?? $rendezVous['date'],
                'heure' => $_POST['heure'] ?? $rendezVous['heure'],
                'motif' => $_POST['motif'] ?? $rendezVous['motif'],
                'statut' => $_POST['statut'] ?? $rendezVous['statut']
            ];

            if ($this->rendezVousRepository->update($id, $data)) {
                header('Location: /rendez-vous');
                exit;
            }
        }

        require_once __DIR__ . '/../Views/rendez-vous/edit.php';
    }

    public function delete($id)
    {
        if ($this->rendezVousRepository->delete($id)) {
            header('Location: /rendez-vous');
            exit;
        }
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['statut'] ?? '';
            if ($this->rendezVousRepository->updateStatus($id, $status)) {
                header('Location: /rendez-vous');
                exit;
            }
        }
    }

    public function parMedecin($medecinId)
    {
        $rendezVous = $this->rendezVousRepository->findByMedecin($medecinId);
        $medecin = $this->medecinRepository->findById($medecinId);
        require_once __DIR__ . '/../Views/rendez-vous/par-medecin.php';
    }

    public function parPatient($patientId)
    {
        $rendezVous = $this->rendezVousRepository->findByPatient($patientId);
        $patient = $this->patientRepository->findById($patientId);
        require_once __DIR__ . '/../Views/rendez-vous/par-patient.php';
    }
}
