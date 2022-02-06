<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Repository\ResumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResumeController
 * @package App\Controller
 * @Route("/api", name="resumeApi")
 */

class ResumeController extends AbstractController
{
    /**
     * @param ResumeRepository $resumeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/cv/{id}", name="get", methods={"GET"})
     */
    public function getResume(ResumeRepository $resumeRepository, $id): Response
    {
        $data = $resumeRepository->find($id);
        return $this->response((array)$data);
    }
    /**
     * @param ResumeRepository $resumeRepository
     * @return JsonResponse
     * @Route("/cv", name="getAll", methods={"GET"})
     */
    public function getAllResume(ResumeRepository $resumeRepository): Response
    {
        $data = $resumeRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ResumeRepository $resumeRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/cv/add", name="add", methods={"POST"})
     */
    public function addResume(Request $request, EntityManagerInterface $entityManager, ResumeRepository $resumeRepository): Response
    {
        try{
        $request = $this->transformJsonBody($request);
        $resume = new Resume();
        $resume->setProfession($request->get('profession'));
        $resume->setCity($request->get('city'));
        $resume->setImage($request->get('image'));
        $resume->setFIO($request->get('FIO'));
        $resume->setPhoneNumber($request->get('phoneNumber'));
        $resume->setEmail($request->get('email'));
        $resume->setBirthdate($request->get('birthdate'));
        $resume->setEducationLevel($request->get('educationLevel'));
        if ($request->get('educationLevel') != 'Среднее'){
            $resume->setUniversity($request->get('university'));
            $resume->setFaculty($request->get('faculty'));
            $resume->setSpecialization($request->get('specialization'));
            $resume->setGraduationYear($request->get('graduationYear'));
        }
        $resume->setSalary($request->get('salary'));
        $resume->setSkills($request->get('keySkills'));
        $resume->setAbout($request->get('about'));
        $resume->setStatus($request->get('status'));
        $entityManager->persist($resume);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => 'Resume added successfully',
        ];
            return $this->response($data);
        }catch(\Exception $e){
            $data = [
                'status' => 422,
                'errors' => 'Data no void',
            ];
            return $this->response($data);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ResumeRepository $resumeRepository
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/cv/{id}/edit", name="edit", methods={"POST"})
     */
    public function editResume(Request $request, EntityManagerInterface $entityManager, ResumeRepository $resumeRepository, $id): Response
    {
        try{
            $resume = $resumeRepository->find($id);
            if (!$resume){
                $data = [
                    'status' => 404,
                    'errors' => "Post not found",
                ];
                return $this->response($data, 404);
            }
            $request = $this->transformJsonBody($request);
            $resume->setProfession($request->get('profession'));
            $resume->setCity($request->get('city'));
            $resume->setImage($request->get('image'));
            $resume->setFIO($request->get('FIO'));
            $resume->setPhoneNumber($request->get('phoneNumber'));
            $resume->setEmail($request->get('email'));
            $resume->setBirthdate($request->get('birthdate'));
            $resume->setEducationLevel($request->get('educationLevel'));
            if ($request->get('educationLevel') != 'Среднее'){
                $resume->setUniversity($request->get('university'));
                $resume->setFaculty($request->get('faculty'));
                $resume->setSpecialization($request->get('specialization'));
                $resume->setGraduationYear($request->get('graduationYear'));
            }
            $resume->setSalary($request->get('salary'));
            $resume->setSkills($request->get('keySkills'));
            $resume->setAbout($request->get('about'));
            $entityManager->flush();
            $data = [
                'status' => 200,
                'errors' => 'Resume updated successfully',
            ];
            return $this->response($data);
        }catch(\Exception $e){
            $data = [
                'status' => 422,
                'errors' => 'Data no void',
            ];
            return $this->response($data);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ResumeRepository $resumeRepository
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/cv/{id}/status/update", name="updateStatus", methods={"POST"})
     */
    public function updateStatusResume(Request $request, EntityManagerInterface $entityManager, ResumeRepository $resumeRepository, $id): Response
    {
        try{
            $resume = $resumeRepository->find($id);
            if (!$resume){
                $data = [
                    'status' => 404,
                    'errors' => "Post not found",
                ];
                return $this->response($data, 404);
            }
            $request = $this->transformJsonBody($request);
            $resume->setStatus($request->get('status'));
            $entityManager->flush();
            $data = [
                'status' => 200,
                'errors' => 'Status updated successfully',
            ];
            return $this->response($data);
        }catch(\Exception $e){
            $data = [
                'status' => 422,
                'errors' => 'Data no void',
            ];
            return $this->response($data);
        }
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = $request->request->all() ? : json_decode($request->getContent(), true);
        $request->request->replace($data);

        return $request;
    }

    function console_log($data){ // сама функция
        if(is_array($data) || is_object($data)){
            echo("<script>console.log('php_array: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('php_string: ".$data."');</script>");
        }
    }
}
