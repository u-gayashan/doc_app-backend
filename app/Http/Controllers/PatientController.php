<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Helper\ResponseHelper;
use App\Service\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    /**
     * @var PatientService
     */

    protected PatientService $patientService;

    /**
     * @var ResponseHelper
     */

    protected ResponseHelper $responseHelper;

    /**
     * @param PatientService $patientService
     * @param ResponseHelper $responseHelper
     */

    public function __construct(PatientService $patientService, ResponseHelper $responseHelper)
    {
        $this->patientService = $patientService;
        $this->responseHelper = $responseHelper;
    }

    /**
     * @param Request $request
     * @return mixed
     */

    public function register(Request $request): mixed
    {
        return $this->patientService->register($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function addNewPatient(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->addNewPatient($request);
        switch ($filteredValue === 1003) {
            case true:
                return $this->responseHelper->response('failed', 'Mobile number already registered', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'New patient successfully added', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getAllPatient(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getAllPatient($request);
        switch ($filteredValue === 1004) {
            case true:
                return $this->responseHelper->response('failed', 'No patients available', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'All patients successfully retrieved', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getPatientByPhone(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getPatientByPhone($request->all());
        switch ($filteredValue === 1001) {
            case true:
                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient for the search term successfully retrieved', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function removePatient(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->removePatient($request);
        switch ($filteredValue === 1001) {
            case true:
                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient details successfully deleted', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function updatePatient(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->updatePatient($request);
        switch ($filteredValue === 1002) {
            case true:
                return $this->responseHelper->response('failed', 'User does not exist', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient details successfully updated', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getPatientDetails(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getPatientDetails($request->all());
        switch ($filteredValue === 1001) {
            case true:
                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient details successfully retrieved', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function addPatientDailyStatus(Request $request): JsonResponse
    {
        $addPatientDailyStatus = $this->patientService->addPatientDailyStatus($request->all());
//        dd($addPatientDailyStatus);
        if ($addPatientDailyStatus['status'] === 1006) {
            return $this->responseHelper->response('failed', 'Parent record does not Exist', null, Response::HTTP_NOT_FOUND);
        } elseif ($addPatientDailyStatus['status'] == true) {
            return $this->responseHelper->response('success', 'daily status transaction is successfully stored', null, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getPatientDailyStatus(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getPatientDailyStatus($request);
        switch ($filteredValue === 1004) {
            case true:
                return $this->responseHelper->response('failed', 'No patients available', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'All patients successfully retrieved', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function addWeightLoseGoalDetails(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->addWeightLoseGoalDetails($request);
//        dd($filteredValue);
        if ($filteredValue == "1005") {
            return $this->responseHelper->response('failed', 'Patient goal details already added', null, Response::HTTP_OK);
        }else if($filteredValue == "1006"){
            return $this->responseHelper->response('failed', 'No patients available', null, Response::HTTP_OK);
        }else if($filteredValue['mobile_number'] !== null){
            return $this->responseHelper->response('success', 'Patient goal details successfully added', $filteredValue, Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getWeightLoseGoalDetailsByMobileNumber(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getWeightLoseGoalDetailsByMobileNumber($request->all());
        switch ($filteredValue === 1001) {
            case true:
                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient weight loss details successfully retrieved', $filteredValue, Response::HTTP_OK);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function addDoctorRecommendation(Request $request): JsonResponse
    {
        $addDoctorRecommendationStatus = $this->patientService->addDoctorRecommendation($request->all());
        switch ($addDoctorRecommendationStatus == true) {
            case true:
                return $this->responseHelper->response('success', 'Recommendation added successfully ', $addDoctorRecommendationStatus, Response::HTTP_OK);
            default:
            case false:
            return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function getPreviousRecommendationByMobileNumber(Request $request): JsonResponse
    {
        $filteredValue = $this->patientService->getPreviousRecommendationByMobileNumber($request->all());
        switch ($filteredValue === 1001) {
            case true:
                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
            default:
            case false:
                return $this->responseHelper->response('success', 'Patient weight loss details successfully retrieved', $filteredValue, Response::HTTP_OK);
        }

    }

//    /**
//     * @param Request $request
//     * @return JsonResponse
//     */

    public function getDailyStatus(Request $request)
    {
        return $this->patientService->getDailyStatus($request->all());
//        switch ($filteredValue == 1001) {
//            case true:
//                return $this->responseHelper->response('failed', 'Invalid mobile number', null, Response::HTTP_OK);
//            default:
//            case false:
//                return $this->responseHelper->response('success', 'Patient status successfully retrieved', $filteredValue, Response::HTTP_OK);
//        }
    }

    public function editPatientByMobileNumber(Request $request)
    {
        $this->patientService->editPatientByMobileNumber($request);

    }


}
