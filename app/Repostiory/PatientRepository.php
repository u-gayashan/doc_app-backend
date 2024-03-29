<?php
/**
 *doctorweb
 *ASUS
 *3/16/2022
 *2:11 AM
 *SRILANKA-AXCITO
 */

namespace App\Repostiory;

use App\Constants\Constants;
use App\Models\DoctorRecommendation;
use App\Models\Patient;
use App\Models\PatientDailyStatus;
use App\Models\PatientGoal;
use App\Models\PatientMedicalRatio;
use App\Models\PatientTechnologicalLiteracy;
use App\Models\PreviousGoalDetails;
use App\Models\User;
use App\Models\WeightLoseGoal;
use App\ServiceInterface\PatientServiceInterface;
use Illuminate\Database\Eloquent\Model;

class PatientRepository implements PatientServiceInterface
{

    /**
     * @param $inputData
     * @return array|string
     */

    public function register($inputData): array|string
    {

        try {
            $user = Patient::where('phone_number', '=', $inputData['phone_number'])->first();
            if ($user === null) {
                $newUser = new Patient();
                $newUser->fill($inputData);
                $newUser->save();
                return ['user_id' => $newUser->id,];
            } else {
                return ['user_id' => null];
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $request
     * @return mixed
     */

    public function getAllPatient($request): mixed
    {
        try {
            return Patient::all()->toArray();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $mobile_number
     * @return mixed
     */

    public function getPatientByPhone($mobile_number): mixed
    {
        return Patient::where('mobile_number', $mobile_number)->get();
    }

    /**
     * @param array $patientDetails
     * @param array $medicalRatio
     * @param array $technologyLiteracy
     * @param array $goal
     * @return array|string
     */

    public function addNewPatient(array $patientDetails, array $medicalRatio, array $technologyLiteracy, array $goal): array|string
    {
        try {
            $user = Patient::where('mobile_number', '=', $patientDetails['mobile_number'])->first();
            if ($user === null) {
                $newUser = new Patient();
                $newUser->fill($patientDetails);
                $newUser->save();

                $newUser = new PatientMedicalRatio();
                $newUser->fill($medicalRatio);
                $newUser->save();

                $newUser = new PatientTechnologicalLiteracy();
                $newUser->fill($technologyLiteracy);
                $newUser->save();

                $newUser = new PatientGoal();
                $newUser->fill($goal);
                $newUser->save();

                return ['mobile_number' => $newUser->mobile_number,];
            } else {
                return ['mobile_number' => null];
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $mobile_number
     * @return string|int
     */

    public function removePatient($mobile_number): string|int
    {
//        return Patients::where('$mobile_number', $mobile_number)->get();
        try {
            $isDeleted = Patient::destroy($mobile_number);
            if ($isDeleted == true) {
                return $isDeleted;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $patientDetails
     * @param $medicalRatio
     * @param $technologyLiteracy
     * @param $goal
     * @return string|int
     */

    public function updatePatient($patientDetails, $medicalRatio, $technologyLiteracy, $goal): string|int
    {
        $userPhone = $patientDetails['mobile_number'];
        $status = '';
        try {
            $patient = Patient::where('mobile_number', $userPhone)->exists();
            if ($patient == true) {

                Patient::where(['mobile_number' => $userPhone])->update(
                    [
                        'first_name' => $patientDetails['first_name'],
                        'last_name' => $patientDetails['last_name'],
                        'dob' => $patientDetails['dob'],
                        'age' => $patientDetails['age'],
                        'ethnicity' => $patientDetails['ethnicity'],
                        'relationship_status' => $patientDetails['relationship_status'],
                        'highest_education' => $patientDetails['highest_education']
                    ]
                );

                PatientMedicalRatio::where(['mobile_number' => $userPhone])->update(
                    [
                        'weight' => $medicalRatio['weight'],
                        'height' => $medicalRatio['height'],
                        'bmi' => $medicalRatio['bmi'],
                        'waist' => $medicalRatio['waist'],
                        'hip' => $medicalRatio['hip'],
                        'waist_hip_ratio' => $medicalRatio['waist_hip_ratio'],
                        'bp' => $medicalRatio['bp'],
                        'lipid_pannel' => $medicalRatio['lipid_pannel'],
                        'a1c' => $medicalRatio['a1c'],
                        'current_health_status' => $medicalRatio['current_health_status']
                    ]
                );

                PatientTechnologicalLiteracy::where(['mobile_number' => $userPhone])->update(
                    [
                        'download_use_skill_level' => $technologyLiteracy['download_use_skill_level'],
                        'search_online_skill_level' => $technologyLiteracy['search_online_skill_level'],
                        'social_media_skill_level' => $technologyLiteracy['social_media_skill_level'],
                        'email_usage_skill_level' => $technologyLiteracy['email_usage_skill_level'],
                        'online_credit_card_usage_skill_level' => $technologyLiteracy['online_credit_card_usage_skill_level']
                    ]
                );

                PatientGoal::where(['mobile_number' => $userPhone])->update(
                    [
                        'selected_goal' => $goal['selected_goal'],
                        'selected_behaviour_change' => $goal['selected_behaviour_change']
                    ]
                );

                $status = 'Data Successfully updated';

            } elseif ($patient == false) {
                $status = Constants::USER_NOT_FOUND;
            }
            return $status;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $mobile_number
     * @return string|array
     */

    public function getPatientDetails($mobile_number): string|array
    {
        try {
            $patientDataArray = [];

            $patient1 = Patient::find($mobile_number)->toArray();
            $patient2 = Patient::find($mobile_number)->getPatientMedicalRatio->toArray();
            $patient3 = Patient::find($mobile_number)->getPatientTechnologicalLiteracy->toArray();
            $patient4 = Patient::find($mobile_number)->getPatientGoal->toArray();

            return array_merge($patient1,$patient2,$patient3,$patient4);

//            $patientDataArray['first_name'] = $patient1->first_name;
//            dd($patientDataArray);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $request
     * @return mixed
     */

    public function addPatientDailyStatus($request): mixed
    {
//        dd($request);

        try {
            $patientDailyStatus = new PatientDailyStatus();
            $patientDailyStatus->fill($request);
            $patientDailyStatus->save();
            return true;
        } catch (\Exception $e) {
            return $e->getCode();
        }

    }

    /**
     * @param $request
     * @return array|string
     */

    public function getPatientDailyStatus($request): array|string
    {
        try {
            return PatientDailyStatus::all()->toArray();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $goalData
     * @return string|array
     */

    public function addWeightLoseGoalDetails($goalData): string|array
    {
        try {
            $patientExist = Patient::where('mobile_number', $goalData['mobile_number'])->exists();
            $goalExist = WeightLoseGoal::where('mobile_number', $goalData['mobile_number'])->exists();
            if ($patientExist == true) {
                if ($goalExist == false) {
                    $newUser = new WeightLoseGoal();
                    $newUser->fill($goalData);
                    $newUser->save();
                    return ['mobile_number' => $newUser->mobile_number,];
                } else {
                    return Constants::PATIENT_ALL_READY_EXIST_IN_PATIENT_GOAL_TABLE;
                }
            } else {
                return Constants::PARENT_RECORD_DOES_NOT_EXIST;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getWeightLoseGoalDetailsByMobileNumber($mobile_number)
    {
//        dd($mobile_number);
//        dd(WeightLoseGoal::find($mobile_number)->toArray());
        try {
            return WeightLoseGoal::where('mobile_number', $mobile_number)->firstOrFail()->toArray();
//            dd(WeightLoseGoal::where('mobile_number', $mobile_number)->firstOrFail()->toArray());
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addDoctorRecommendation($data)
    {

        try {
            $doctorRecommendation = new DoctorRecommendation();
            $doctorRecommendation->fill($data);
            $doctorRecommendation->save();
            return true;
        } catch (\Exception $e) {
            return $e->getCode();
        }
    }

    public function getPreviousRecommendationByMobileNumber($mobile_number)
    {
        try {
            return DoctorRecommendation::where('mobile_number', $mobile_number)->get()->toArray();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $mobile_number
     * @return array|string
     */

    public function getDailyStatus($mobile_number): array|string
    {
        try {
            $patientPersonalDetails = Patient::find($mobile_number)->firstOrFail()->toArray();
            $patientHealthDetails = Patient::find($mobile_number)->getPatientMedicalRatio->toArray();
            $patientGoal = Patient::find($mobile_number)->getPatientGoal->toArray();
            $patientRecommendation = Patient::find($mobile_number)->getPatientRecommendation->toArray();

            return array_merge($patientPersonalDetails,$patientHealthDetails,$patientGoal,$patientRecommendation);

        }catch (\Exception $e) {
            return $e->getCode();
        }

    }

    public function editPatientByMobileNumber($data)
    {
        $patientPatientGoalDetails = Patient::find($data['mobile_number'])->getPatientGoalDetails->toArray();
//        dd($patientPatientGoalDetails);
        unset($patientPatientGoalDetails["id"]);
//        dd($patientPatientGoalDetails);
        $previousGoalDetails = new PreviousGoalDetails();
        $previousGoalDetails->fill($patientPatientGoalDetails);
        $previousGoalDetails->save();
        WeightLoseGoal::where(['mobile_number' => $data['mobile_number']])->update(
            [
                'goal_id' => $data['goal_id'],
                'goal' => $data['goal'],
                'motivation_factor_for_goal_manual' => $data['motivation_factor_for_goal_manual'],
                'motivation_factor_for_goal_selected' => $data['motivation_factor_for_goal_selected'],
                'level_of_motivation' => $data['level_of_motivation'],
                'motivation_target_10_text' => $data['motivation_target_10_text'],
                'contribute_favtors_selected' => $data['contribute_favtors_selected'],
                'no_one_fouca_factor' => $data['no_one_fouca_factor'],
                'previously_attempted' => $data['previously_attempted'],
                'attempted_yes_success_factors' => $data['attempted_yes_success_factors'],
                'attempted_yes_success_challenges' => $data['attempted_yes_success_challenges'],
                'attempted_no_success_factors' => $data['attempted_no_success_factors'],
                'attempted_no_success_challenges' => $data['attempted_no_success_challenges'],
                'confident_factor' => $data['confident_factor'],
                'confident_to_change_encouragement' => $data['confident_to_change_encouragement'],
                'look_forward_factors' => $data['look_forward_factors'],
            ]
        );
    }
}
