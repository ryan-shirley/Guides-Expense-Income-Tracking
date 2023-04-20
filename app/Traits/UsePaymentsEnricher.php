<?php

namespace App\Traits;

trait UsePaymentsEnricher
{
    use Imagehandler;

    public function Enrich($payments) {
        if($payments) {
            foreach ($payments as $index => $payment) {
                $payments[$index]->keyID = "p_" . $payment->ref_id;
                $payments[$index]->code = $payment->code != null ? $payment->code : "N/A";
                $payments[$index]->receipt_url = $this->GetReceiptUrl($payment->ref_id, $payment->_id);

                switch ($payment->code) {
                    case 1:
                        $payments[$index]->code_name = "Programme Material";
                        break;
                    case 2:
                        $payments[$index]->code_name = "Uniform & Badges";
                        break;
                    case 3:
                        $payments[$index]->code_name = "Camps / Outings / Events";
                        break;
                    case 4:
                        $payments[$index]->code_name = "IGG Membership Fees";
                        break;
                    case 5:
                        $payments[$index]->code_name = "Equipment";
                        break;
                    case 6:
                        $payments[$index]->code_name = "Unit Administration";
                        break;
                    case 7:
                        $payments[$index]->code_name = "Rent";
                        break;
                    case 8:
                        $payments[$index]->code_name = "Adult Training";
                        break;
                    case 9:
                        $payments[$index]->code_name = "Thinking Day";
                        break;
                    case 10:
                        $payments[$index]->code_name = "Banking Fees";
                        break;
                    case 11:
                        $payments[$index]->code_name = "Miscellaneous";
                        break;
                    case 12:
                        $payments[$index]->code_name = "Fundraising";
                        break;
                    case 13:
                        $payments[$index]->code_name = "If drawing cash from bank or float (petty cash)";
                        break;
                    default:
                        echo "N/A";
                }

                if($payment->is_cash) {
                    $payments[$index]->cash_only = $payment->amount;
                    $payments[$index]->other = 0;
                } else {
                    $payments[$index]->cash_only = 0;
                    $payments[$index]->other = $payment->amount;
                }
            }
        }

        return $payments;
    }
}
