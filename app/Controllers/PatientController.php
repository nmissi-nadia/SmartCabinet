<?php

namespace App\Controllers;

use App\Repositories\PatientRepository;
use PDO;

class PatientController extends Controller
{
    private PatientRepository $patientRepository;

    public function __construct()
    {
        $this->patientRepository = new PatientRepository($this->getConnection());
    }

    public function index()
    {
        $patients = $this->patientRepository->getAll();
        $this->render('patients/index', [
            'patients' => $patients,
            'title' => 'Liste des patients',
            'currentPage' => 'patients'
        ]);
    }

    public function create()
    {
        if ($this->isPost()) {
            if (!$this->validateCSRF()) {
                $this->setFlash('error', 'Token CSRF invalide');
                $this->redirect('/patients/create');
                return;
            }

            try {
                $userData = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'telephone' => $_POST['telephone']
                ];

                $patientData = [
                    'numero_securite_sociale' => $_POST['numero_securite_sociale'],
                    'date_naissance' => $_POST['date_naissance']
                ];

                $this->patientRepository->createWithUser($userData, $patientData);
                $this->setFlash('success', 'Patient créé avec succès');
                $this->redirect('/patients');
            } catch (\Exception $e) {
                $this->setFlash('error', 'Erreur lors de la création du patient');
                $this->redirect('/patients/create');
            }
        }

        $this->render('patients/create', [
            'title' => 'Nouveau patient',
            'currentPage' => 'patients',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function edit($id)
    {
        $patient = $this->patientRepository->getById($id);
        
        if (!$patient) {
            $this->setFlash('error', 'Patient non trouvé');
            $this->redirect('/patients');
            return;
        }

        if ($this->isPost()) {
            if (!$this->validateCSRF()) {
                $this->setFlash('error', 'Token CSRF invalide');
                $this->redirect("/patients/edit/$id");
                return;
            }

            try {
                $data = [
                    'numero_securite_sociale' => $_POST['numero_securite_sociale'],
                    'date_naissance' => $_POST['date_naissance']
                ];

                $this->patientRepository->update($id, $data);
                $this->setFlash('success', 'Patient mis à jour avec succès');
                $this->redirect('/patients');
            } catch (\Exception $e) {
                $this->setFlash('error', 'Erreur lors de la mise à jour du patient');
                $this->redirect("/patients/edit/$id");
            }
        }

        $this->render('patients/edit', [
            'patient' => $patient,
            'title' => 'Modifier le patient',
            'currentPage' => 'patients',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function delete($id)
    {
        if (!$this->validateCSRF()) {
            $this->setFlash('error', 'Token CSRF invalide');
            $this->redirect('/patients');
            return;
        }

        try {
            $this->patientRepository->delete($id);
            $this->setFlash('success', 'Patient supprimé avec succès');
        } catch (\Exception $e) {
            $this->setFlash('error', 'Erreur lors de la suppression du patient');
        }

        $this->redirect('/patients');
    }

    private function getConnection(): PDO
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'smartcabinet';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';

        return new PDO(
            "pgsql:host=$host;dbname=$dbname",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
