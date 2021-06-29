<?php

namespace Database\Seeders;

use App\Models\Finance\WalletTransaction;
use App\Models\Support\Chat;
use App\Models\Support\ChatMessage;
use App\Models\Support\ChatSurvey;
use App\Models\Service\Job;
use App\Models\User\Admin;
use App\Models\App\Adviser;
use App\Models\App\Bank;
use App\Models\Service\Business;
use App\Models\Service\Car;
use App\Models\Transaction\Card;
use App\Models\Service\CarOrder;
use App\Models\App\Category;
use App\Models\Service\Consultation;
use App\Models\Service\ConsultationDay;
use App\Models\Corporate\Corporate;
use App\Models\Corporate\CorporateDay;
use App\Models\Corporate\CorporatePattern;
use App\Models\App\Country;
use App\Models\User\Customer;
use App\Models\Transaction\HighTransaction;
use App\Models\App\SecurityQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\App\Notification;
use App\Models\Finance\Payment;
use App\Models\Service\Product;
use App\Models\Service\ProductOrder;
use App\Models\Service\ProductStorage;
use App\Models\Service\Service;
use App\Models\Service\ServiceItem;
use App\Models\Service\ServiceOrder;
use App\Models\Service\ServiceOrderDocument;
use App\Models\Service\ServiceStep;
use App\Models\Transaction\Transaction;
use App\Models\Service\Translation;
use App\Models\Service\Article;
use App\Models\Support\Comment;
use App\Models\Finance\CurrencyBuy;
use App\Models\User\CustomerStatus;
use App\Models\Transaction\ExchangeBranchCity;
use App\Models\Service\InternationalPayment;
use App\Models\Service\InternationalPaymentInput;
use App\Models\Service\InternationalPaymentOrder;
use App\Models\Service\ProductFeature;
use App\Models\Finance\Trader;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



//         App\Models\User\Admin::factory(5)->create();
            $this->call(RequiresSeeder::class);


    }
}
