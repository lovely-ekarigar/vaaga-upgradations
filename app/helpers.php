<?php

use App\Helpers\General\Timezone;
use App\Helpers\General\HtmlHelper;
use App\Models\Course;
/*
 * Global helpers file with misc functions.
 */
if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}

if (!function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('home_route')) {

    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend') && auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            } else {
                return 'frontend.index';
            }
        }

        return 'frontend.index';
    }
}

if (!function_exists('active_class')) {
    /**
     * Get the active class for an element if the condition is met.
     * Used for sidebar/ nav active states.
     *
     * @param bool $condition
     * @param string $activeClass
     * @return string
     */
    function active_class($condition, $activeClass = 'active')
    {
        return $condition ? $activeClass : '';
    }
}

if (!function_exists('style')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (!function_exists('getCourseType')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function getCourseType($string)
    {
        // dd($string);

         $list = array(
            "onetoone_full"=>"1:1 Full Course",
            "onetoone_monthly"=>"1:1 Monthly Subscription",
            "onetomany_full"=>"1:N Full Course",
            "onetomany_monthly"=>"1:N Monthly Subscription",
            "quarterly"=>" Quarterly Subscription",
            "monthly"=>" 1:N Monthly Subscription",
            "full"=>"1:N Full Course",
            "test series"=>"Test Series",
            "regular_monthly"=>"Regular Monthly Subscription",
            "regular_monthly_1"=>"1:1 Regular Monthly Subscription",
            ""=>""
        );


        return $list[$string] ?? $string;
    }
}



if (!function_exists('script')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}

if (!function_exists('form_cancel')) {

    /**
     * @param        $cancel_to
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_cancel($cancel_to, $title, $classes = 'btn btn-danger ')
    {
        return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
    }
}

if (!function_exists('form_submit')) {

    /**
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_submit($title, $classes = 'btn btn-success pull-right')
    {
        return resolve(HtmlHelper::class)->formSubmit($title, $classes);
    }
}

if (!function_exists('camelcase_to_word')) {

    /**
     * @param $str
     *
     * @return string
     */
    function camelcase_to_word($str)
    {
        return implode(' ', preg_split('/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x', $str));
    }
}

if (!function_exists('contact_data')) {

    /**
     * @param $str
     *
     * @return array
     */
    function contact_data($str)
    {
        $newElements = [];
        $elements = json_decode($str);
        foreach ($elements as $key => $item) {
            $newElements[$item->name] = ['value' => $item->value, 'status' => $item->status];
        }
        return $newElements;
    }
}

if (!function_exists('section_filter')) {

    /**
     * @param $str
     * Filter according to type selected.
     * 1 = Popular Categories
     * 2 = Featured Course
     * 3 = Trending Courses
     * 4 = Popular Courses
     * 5 = Custom Links
     * @return array
     */
    function section_filter($section)
    {
        $type = $section->type;
        $section_data = "";
        $section_title = "";
        $content = [];

        if ($type == 1) {
            $section_content = \App\Models\Category::has('courses', '>', 7)
                ->where('status', '=', 1)->get()->take(6);
            $section_title = trans('labels.frontend.footer.popular_categories');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->name,
                    'link' => route('courses.category', ['category' => $item->slug])
                ];
                $content[] = $single_item;
            }
        } else if ($type == 2) {
            $section_content = \App\Models\Course::where('featured', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.featured_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 3) {
            $section_content = \App\Models\Course::where('trending', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.trending_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 4) {
            $section_content = \App\Models\Course::where('popular', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.popular_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 5) {
            $section_title = trans('labels.frontend.footer.useful_links');
            $section_content = $section->links;
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->label,
                    'link' => $item->link
                ];
                $content[] = $single_item;
            }
        }

        return ['section_content' => $content, 'section_title' => $section_title];
    }
}

if (!function_exists('buildBillingPeriodFromAnchorDate')) {

    /**
     * Build billing period where month/year comes from created date offsets
     * and day-of-month comes from anchor date (end_date preferred).
     */
    function buildBillingPeriodFromAnchorDate($createdAt, $anchorDate, $startMonths = 0, $endMonths = 1)
    {
        $created = \Carbon\Carbon::parse($createdAt);
        $anchor = !empty($anchorDate) ? \Carbon\Carbon::parse($anchorDate) : $created;
        $anchorDay = (int) $anchor->day;

        $startBase = $created->copy()->addMonthsNoOverflow((int) $startMonths);
        $endBase = $created->copy()->addMonthsNoOverflow((int) $endMonths);

        $startDate = $startBase->copy()->day(min($anchorDay, $startBase->daysInMonth));
        $endDate = $endBase->copy()->day(min($anchorDay, $endBase->daysInMonth));

        return $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
    }
}

if (!function_exists('showInvoiceSubs')) {

    function showInvoiceSubs($order,$subscription,$type)
    {
        $invoice = new \App\Http\Controllers\Traits\InvoiceGenerator();
        $invoice->number($order->id.'-'.$subscription->id);
        $invoice->addOrderInfo($subscription->reference_no);
        if (!empty($order->remarks)) {
            $invoice->addRemark($order->remarks);
        }
        $invoice->addDate($subscription->renew_date ?: $subscription->created_at);
            if(str_contains($order->course_mode,"monthly")){
            // Subscription billing period is always calculated from subscription end date:
            // start = end_date - 1 month, end = end_date.
            $periodEnd = !empty($subscription->end_date)
                ? \Carbon\Carbon::parse($subscription->end_date)
                : \Carbon\Carbon::parse($subscription->renew_date ?: $subscription->created_at);
            $periodStart = $periodEnd->copy()->subMonthNoOverflow();
            $month = $periodStart->format('d M Y') . ' - ' . $periodEnd->format('d M Y');

            $invoice->addMonth($month);
         }

        $itemCount = $order->items->count() ?: 1;
        // Calculate price from orders table (amount + discount) divided by item count
        $grossAmount = $subscription->amount + ($subscription->discount ?? 0);
        $pricePerItem = round($grossAmount / $itemCount, 2);

        foreach ($order->items as $item) {
            $cx = new Course();

            $title = $cx->getCouseNameWithCat($item->item->id);

            $price = $pricePerItem;

            $qty = 1;
            $id = 'prod-'.$item->item->id;
            $invoice->addItem($title, $price, $qty, $id);
        }
        $total = $subscription->amount;
        if($subscription->discount){
         $invoice->addDiscountData($subscription->discount);
        }
        
     
        $invoice->addTotal($total);
        $invoice->addTaxData($subscription->gst);

        $user = \App\Models\Auth\User::find($subscription->user_id);
        $invoice->customer([
            'name' => $user->full_name,
            'id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        $invoiceEntry = \App\Models\Invoice::where('order_id','=',$order->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new \App\Models\Invoice();
            $invoiceEntry->user_id = $order->user_id;
            $invoiceEntry->order_id = $order->id;
            $invoiceEntry->url = 'invoice-'.$order->id.'.pdf';
            $invoiceEntry->save();
        }

        if($type=='show'){
            return $invoice->show('invoice-'.$order->id.'-'.$subscription->id.'.pdf');
        }
        if($type=='download'){
            return $invoice->download('invoice-'.$order->id.'-'.$subscription->id.'.pdf');
        }
    }
}



if (!function_exists('showInvoice')) {

    function showInvoice($order,$type)
    {
        $invoice = new \App\Http\Controllers\Traits\InvoiceGenerator();
        $invoice->number($order->id);
        $invoice->addOrderInfo($order->reference_no);
        if (!empty($order->remarks)) {
            $invoice->addRemark($order->remarks);
        }
        $invoice->addDate($order->created_at);
            if(str_contains($order->course_mode,"monthly")){
                $orderCreatedAt = \Carbon\Carbon::parse($order->created_at);
                $periodStart = $orderCreatedAt->copy();

                // If student's assigned batch starts after order date,
                // use batch start date as billing period start.
                $courseItemIds = $order->items
                    ->where('item_type', \App\Models\Course::class)
                    ->pluck('item_id')
                    ->filter()
                    ->unique()
                    ->values();

                if ($courseItemIds->isNotEmpty()) {
                    $enrolledBatch = \App\Models\StudentTeacherBatch::query()
                        ->join('batches', 'batches.id', '=', 'student_teacher_batches.bid')
                        ->where('student_teacher_batches.uid', $order->user_id)
                        ->whereIn('batches.cid', $courseItemIds)
                        ->whereNotNull('batches.start_date')
                        ->orderBy('batches.start_date', 'asc')
                        ->select('batches.start_date')
                        ->first();

                    if ($enrolledBatch && !empty($enrolledBatch->start_date)) {
                        $batchStartDate = \Carbon\Carbon::parse($enrolledBatch->start_date);
                        if ($batchStartDate->gt($orderCreatedAt)) {
                            $periodStart = $batchStartDate;
                        }
                    }
                }

                $periodEnd = $periodStart->copy()->addMonthNoOverflow();
                $month = $periodStart->format('d M Y') . ' - ' . $periodEnd->format('d M Y');

            $invoice->addMonth($month);
         }


        $hasRemarks = !empty($order->remarks);
        $itemCount = $order->items->count() ?: 1;
        // Calculate price from orders table (amount + discount) divided by item count
        $grossAmount = $order->amount + ($order->discount ?? 0);
        $pricePerItem = round($grossAmount / $itemCount, 2);

        foreach ($order->items as $item) {
            $cx = new Course();

            $title = $cx->getCouseNameWithCat($item->item->id);

            $price = $pricePerItem;

            $qty = 1;
            $id = 'prod-'.$item->item->id;
            $invoice->addItem($title, $price, $qty, $id);
        }
        if ($hasRemarks) {
            $discount = $order->discount ?? 0;
            if ($discount > 0) {
                $invoice->addDiscountData($discount);
            }
            $total = $order->amount;
        } else {
            $total = $order->amount;
            $discount = $order->discount ?? 0;
            if ($discount > 0) {
             $invoice->addDiscountData($discount);
            }
        }

        $invoice->addTotal($total);
        $invoice->addTaxData($order->gst);

        $user = \App\Models\Auth\User::find($order->user_id);
        $invoice->customer([
            'name' => $user->full_name,
            'id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);

        $invoiceEntry = \App\Models\Invoice::where('order_id','=',$order->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new \App\Models\Invoice();
            $invoiceEntry->user_id = $order->user_id;
            $invoiceEntry->order_id = $order->id;
            $invoiceEntry->url = 'invoice-'.$order->id.'.pdf';
            $invoiceEntry->save();
        }

        if($type=='show'){
            return $invoice->show('invoice-'.$order->id.'.pdf');
        }
        if($type=='download'){
            return $invoice->download('invoice-'.$order->id.'.pdf');
        }
    }
}

if (!function_exists('generateInvoice')) {

    function generateInvoice($order)
    {
        $invoice = new \App\Http\Controllers\Traits\InvoiceGenerator();
        $invoice->number($order->id);

        // Calculate price from orders table (amount + discount) divided by item count
        $itemCount = $order->items->count() ?: 1;
        $grossAmount = $order->amount + ($order->discount ?? 0);
        $pricePerItem = round($grossAmount / $itemCount, 2);

        foreach ($order->items as $item) {
               $cx = new Course();

            $title = $cx->getCouseNameWithCat($item->item->id);
            $price = $pricePerItem;
            $qty = 1;
            $id = 'prod-'.$item->item->id;
            $invoice->addItem($title, $price, $qty, $id);
        }
//        $invoice->number($order->id);
        $total = $order->items->sum('price');

        $coupon = \App\Models\Coupon::find($order->coupon_id);
        if($coupon != null){
            $discount =  $order->items->sum('price') * $coupon->amount/100;
            $invoice->addDiscountData($discount);
            $total = $total - $discount;
        }
        $taxes = \App\Models\Tax::where('status','=',1)->get();
        $rateSum = \App\Models\Tax::where('status','=',1)->sum('rate');
        if($taxes != null){
            $taxData = [];
            foreach ($taxes as $tax){

                $taxData [] = ['name'=>$tax->name,'amount' => $total * $tax->rate/100];
            }
            // $invoice->addTaxData($taxData);
            $total =  $total + ($total * $rateSum/100);
        }
        $invoice->addTotal($total);
        $user = \App\Models\Auth\User::find($order->user_id);
  
       $x= $invoice->customer([
                'name' => $user->full_name,
                'id' => $user->id,
                'email' => $user->email
            ])
            ->save('public/invoices/invoice-'.$order->id.'.pdf');
//                ->download('invoice-'.$order->id.'.pdf');
               // ->show('invoice-'.$order->id.'.pdf');
  // dd($x);

        $invoiceEntry = \App\Models\Invoice::where('order_id','=',$order->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new \App\Models\Invoice();
            $invoiceEntry->user_id = $order->user_id;
            $invoiceEntry->order_id = $order->id;
            $invoiceEntry->url = 'invoice-'.$order->id.'.pdf';
            $invoiceEntry->save();
        }

    }
}

if (!function_exists('trashUrl')) {

    /**
     * @param $str
     *
     * @return array
     */
    function trashUrl($request)
    {
        $currentQueries = $request->query();

//Declare new queries you want to append to string:
        $newQueries = ['show_deleted' => 1];

//Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $newQueries);

//Generate the URL with all the queries:
        return $request->fullUrlWithQuery($allQueries);

    }
}

if (!function_exists('getCurrency')) {

    /**
     * @param $str
     *
     * @return array
     */
    function getCurrency($short_code)
    {
        $currencies = config('currencies');
        $currency = null;
        if (!empty($short_code)) {
            foreach ($currencies as $key => $val) {
                if (isset($val['short_code']) && $val['short_code'] == $short_code) {
                    $currency = $val;
                    break;
                }
            }
        }
        // Default to INR (Indian Rupee) when not found or empty - app is India-focused
        if (empty($currency) && !empty($currencies)) {
            foreach ($currencies as $val) {
                if (isset($val['short_code']) && $val['short_code'] === 'INR') {
                    $currency = $val;
                    break;
                }
            }
            $currency = $currency ?: reset($currencies);
        }
        return is_array($currency) ? $currency : ['short_code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupees', 'country' => 'India'];
    }
}

if (!function_exists('menuList')) {


    function menuList($array)
    {
        $temp_array = array();
        foreach ($array as $item) {
            if ($item->getsons($item->id)->except($item->id)) {
                $item->subs = menuList($item->getsons($item->id)->except($item->id)); // here is the recursion
                $temp_array[] = $item;
            }
        }
        return $temp_array;
    }
}

// Active package replacement for Laravel 10 compatibility
if (!class_exists('HieuLe\Active\Facades\Active')) {
    class Active {
        public static function checkUriPattern($patterns) {
            $request = request();
            $currentUri = $request->path();
            
            if (!is_array($patterns)) {
                $patterns = [$patterns];
            }
            
            foreach ($patterns as $pattern) {
                if (str_contains($pattern, '*')) {
                    $pattern = str_replace('*', '.*', $pattern);
                    if (preg_match('#^' . $pattern . '$#', $currentUri)) {
                        return true;
                    }
                } elseif ($currentUri === $pattern || str_starts_with($currentUri, $pattern)) {
                    return true;
                }
            }
            
            return false;
        }
        
        public static function checkRoute($route) {
            return request()->routeIs($route);
        }
        
        public static function checkUri($uris) {
            $request = request();
            $currentUri = $request->path();
            
            if (!is_array($uris)) {
                $uris = [$uris];
            }
            
            return in_array($currentUri, $uris);
        }
    }
}

// Create namespace alias
if (!class_exists('HieuLe\Active\Facades\Active')) {
    class_alias('Active', 'HieuLe\Active\Facades\Active');
}



