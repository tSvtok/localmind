<?php
    namespace App\Http\Services;

    use App\Http\Repository\AuthRepository;

class AuthService
    {
        private $AuthRepository;

        public function __construct(AuthRepository $authRepository)
        {
            $this->AuthRepository = $authRepository;
        }

        public function login($email, $password)
        {
            return $this->AuthRepository->login($email, $password);
        }

        public function register($Full_name,$City ,$Email, $password)
        {
            return $this->AuthRepository->register($Full_name, $City ,$Email, $password);
        }
    }

