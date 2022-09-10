<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use App\Models\PurchaseTransaction;
use App\Models\campaignVoucher;

class VoucherController extends Controller
{

    public function customerEligibleChecks(Request $request)
    {

        $customer = Customer::where("id", $request['customer_id'])->first();

        if( !$customer ) { // Check customer exists?
            return response()->json([ "success" => false, "message" => "customer_not_found" ]);
        }

        $eligible = PurchaseTransaction::select(DB::raw('count(*) as count, sum(total_spent) as total_spent'))
                   ->where('customer_id', $request['customer_id'])
                   ->whereRaw('DATE(transaction_at) >= (DATE(NOW()) - INTERVAL 30 DAY)')
                   ->groupBy('customer_id')->first();

        if( isset($eligible) && $eligible['count'] >= 3 && $eligible['total_spent'] >= 100 ) { // Check eligible?

            $customerVoucher = campaignVoucher::where('customer_id', $customer->id)->first();

            if( !$customerVoucher ) { // Check customer's voucher exists?
                $voucher = campaignVoucher::where('customer_id', null)->first();

                if( !$voucher ) { // Check voucher fully redeem?
                    return response()->json([ "success" => false, "message" => "campagin_voucher_fully_redeemed" ]);
                }

                $lockedVoucher = campaignVoucher::where('id', $voucher->id)->update(['customer_id' => $customer->id, 'locked_at' => now()]);

                return response()->json([ "success" => true, "message" => "customer_eligible_for_campaign"]);
            } else {

                if( $customerVoucher->redeemed_at != null ) { // Check voucher redeem? 
                    return response()->json([ "success" => true, "message" => "customer_voucher_redeemed", "data" => $customerVoucher ]);
                }

                $lockedTime = strtotime($customerVoucher->locked_at);

                if( time() - $lockedTime > 10 * 60 ) { // Check locked time exceeded 10mins

                    $unlockedVoucher = campaignVoucher::where('id', $customerVoucher->id)->update(['customer_id' => null, 'locked_at' => null]); // Release voucher to others

                    return response()->json([ "success" => false, "message" => "submissions_time_exceeded" ]);
                }

                return response()->json([ "success" => true, "message" => "customer_eligible_for_campaign"]);
            }
        }

        return response()->json([ "success" => false, "message" => "customer_not_eligibled" ]);
    }

    public function validateSubmissions(Request $request)
    {
        $checkImage = $this->validateImageRecognition($request['is_image']); // Validate image recognition API

        $customer = Customer::where("id", $request['customer_id'])->first();

        if( !$customer ) { // Check customer exists?
            return response()->json([ "success" => false, "message" => "customer_not_found" ]);
        }

        $voucher = campaignVoucher::where('customer_id', $customer->id)->first();

        if( !$voucher ) { // Check customer's voucher exists? 
            return response()->json([ "success" => false, "message" => "customer_voucher_not_found" ]);
        }

        if( $voucher->redeemed_at != null ) { // Check voucher redeem?
            return response()->json([ "success" => true, "message" => "customer_voucher_redeemed", "data" => $voucher ]);
        }

        $lockedTime = strtotime($voucher->locked_at);

        if( $checkImage === false || (time() - $lockedTime > 10 * 60) ) { // Image recognition return false and locked time exceeded 10mins
            $unlockedVoucher = campaignVoucher::where('id', $voucher->id)->update(['customer_id' => null, 'locked_at' => null]); // Release voucher to others

            return response()->json([ "success" => false, "message" => "submissions_time_exceeded" ]);
        }

        $redeemedVoucher = campaignVoucher::where('id', $voucher->id)->update(['redeemed_at' => now()]); // Mark this voucher is redeemed with date given

        if( $redeemedVoucher ) { // Success!!
            $voucher = campaignVoucher::where('customer_id', $customer->id)->first();
            return response()->json([ "success" => true, "message" => "submissions_successfully", "data" => $voucher ]);
        }

        return response()->json([ "success" => false, "message" => "submissions_image_unsuccessful" ]); // Error!!
    }

    private function validateImageRecognition($is_image)
    {
        return filter_var($is_image, FILTER_VALIDATE_BOOLEAN);
    }
}
