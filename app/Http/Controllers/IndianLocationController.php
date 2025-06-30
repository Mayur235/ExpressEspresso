<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndianLocationController extends Controller
{
    private $states = [
        ['id' => 1, 'name' => 'Andhra Pradesh'],
        ['id' => 2, 'name' => 'Arunachal Pradesh'],
        ['id' => 3, 'name' => 'Assam'],
        ['id' => 4, 'name' => 'Bihar'],
        ['id' => 5, 'name' => 'Chhattisgarh'],
        ['id' => 6, 'name' => 'Goa'],
        ['id' => 7, 'name' => 'Gujarat'],
        ['id' => 8, 'name' => 'Haryana'],
        ['id' => 9, 'name' => 'Himachal Pradesh'],
        ['id' => 10, 'name' => 'Jharkhand'],
        ['id' => 11, 'name' => 'Karnataka'],
        ['id' => 12, 'name' => 'Kerala'],
        ['id' => 13, 'name' => 'Madhya Pradesh'],
        ['id' => 14, 'name' => 'Maharashtra'],
        ['id' => 15, 'name' => 'Manipur'],
        ['id' => 16, 'name' => 'Meghalaya'],
        ['id' => 17, 'name' => 'Mizoram'],
        ['id' => 18, 'name' => 'Nagaland'],
        ['id' => 19, 'name' => 'Odisha'],
        ['id' => 20, 'name' => 'Punjab'],
        ['id' => 21, 'name' => 'Rajasthan'],
        ['id' => 22, 'name' => 'Sikkim'],
        ['id' => 23, 'name' => 'Tamil Nadu'],
        ['id' => 24, 'name' => 'Telangana'],
        ['id' => 25, 'name' => 'Tripura'],
        ['id' => 26, 'name' => 'Uttar Pradesh'],
        ['id' => 27, 'name' => 'Uttarakhand'],
        ['id' => 28, 'name' => 'West Bengal'],
        ['id' => 29, 'name' => 'Delhi'],
        ['id' => 30, 'name' => 'Jammu and Kashmir'],
        ['id' => 31, 'name' => 'Ladakh'],
        ['id' => 32, 'name' => 'Puducherry'],
        ['id' => 33, 'name' => 'Chandigarh'],
        ['id' => 34, 'name' => 'Andaman and Nicobar Islands'],
        ['id' => 35, 'name' => 'Dadra and Nagar Haveli and Daman and Diu'],
        ['id' => 36, 'name' => 'Lakshadweep'],
    ];

    private $cities = [
        1 => ['Visakhapatnam', 'Vijayawada', 'Guntur'],
        2 => ['Itanagar', 'Tawang', 'Pasighat'],
        3 => ['Guwahati', 'Dibrugarh', 'Jorhat'],
        4 => ['Patna', 'Gaya', 'Bhagalpur'],
        5 => ['Raipur', 'Bhilai', 'Korba'],
        6 => ['Panaji', 'Margao', 'Vasco da Gama'],
        7 => ['Ahmedabad', 'Surat', 'Vadodara'],
        8 => ['Chandigarh', 'Karnal', 'Panipat'],
        9 => ['Shimla', 'Mandi', 'Solan'],
        10 => ['Ranchi', 'Jamshedpur', 'Dhanbad'],
        11 => ['Bengaluru', 'Mysore', 'Mangalore'],
        12 => ['Thiruvananthapuram', 'Kochi', 'Kozhikode'],
        13 => ['Bhopal', 'Indore', 'Gwalior'],
        14 => ['Mumbai', 'Pune', 'Nagpur'],
        15 => ['Imphal', 'Thoubal', 'Churachandpur'],
        16 => ['Shillong', 'Tura', 'Nongstoin'],
        17 => ['Aizawl', 'Lunglei', 'Champhai'],
        18 => ['Kohima', 'Mokokchung', 'Wokha'],
        19 => ['Bhubaneswar', 'Cuttack', 'Rourkela'],
        20 => ['Amritsar', 'Ludhiana', 'Jalandhar'],
        21 => ['Jaipur', 'Udaipur', 'Jodhpur'],
        22 => ['Gangtok', 'Namchi', 'Geyzing'],
        23 => ['Chennai', 'Coimbatore', 'Madurai'],
        24 => ['Hyderabad', 'Warangal', 'Nizamabad'],
        25 => ['Agartala', 'Udaipur', 'Dharmanagar'],
        26 => ['Lucknow', 'Kanpur', 'Varanasi'],
        27 => ['Dehradun', 'Haridwar', 'Roorkee'],
        28 => ['Kolkata', 'Howrah', 'Durgapur'],
        29 => ['New Delhi'],
        30 => ['Srinagar', 'Jammu'],
        31 => ['Leh', 'Kargil'],
        32 => ['Puducherry'],
        33 => ['Chandigarh'],
        34 => ['Port Blair'],
        35 => ['Silvassa', 'Daman', 'Diu'],
        36 => ['Kavaratti'],
    ];

    public function states()
    {
        $states = array_map(function ($state) {
            return [
                'province_id' => $state['id'],
                'province' => $state['name'],
            ];
        }, $this->states);

        return response()->json($states);
    }

    public function cities($state_id)
    {
        $citiesRaw = $this->cities[$state_id] ?? [];
        $cities = array_map(function ($city, $index) {
            return [
                'city_id' => $index + 1,
                'type' => 'City',
                'city_name' => $city,
            ];
        }, $citiesRaw, array_keys($citiesRaw));

        return response()->json($cities);
    }
}
