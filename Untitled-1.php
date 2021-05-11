if($request->ajax()){
    // custom var
    $output = '';
    $ual_paginate = '';
    $ual_total_results = '';
    // get all request
    $ual_rangefrom = $request->get('ual_rangefrom');
    $ual_rangeTo   = $request->get('ual_rangeTo');
    $ual_category  = $request->get('ual_category');
    $page         = $request->get('page');
    // to lower values
    $toLower_category = Str::lower($ual_category);
    // query
    $filter_user_logs_table = Useractivites::where('act_respo_user_id', $user_id)
                            ->where(function($query) use ($ual_rangefrom, $ual_rangeTo, $ual_category, $toLower_category){
                                if($ual_category != 0 OR !empty($ual_category)){
                                    $query->where('act_type', '=', $toLower_category);
                                }
                                if($ual_rangefrom != 0 OR !empty($ual_rangefrom) OR !is_null($ual_rangefrom) AND $ual_rangeTo != 0 OR !empty($ual_rangeTo) OR !is_null($ual_rangeTo)){
                                    $query->whereBetween('created_at', [$ual_rangefrom, $ual_rangeTo]);
                                }
                            })
                            ->orderBy('created_at', 'DESC')
                            ->paginate(10);
    // total filtered date
    $al_count_Filtered_result = count($filter_user_logs_table);
    $ual_totalFiltered_result = $filter_user_logs_table->total();
    // custom values
    if($ual_totalFiltered_result > 0){
        if($ual_totalFiltered_result > 1){
            $s = 's';
        }else{
            $s = '';
        }
        $ual_total_results = $filter_user_logs_table->firstItem() . ' - ' . $filter_user_logs_table->lastItem() . ' of ' . $ual_totalFiltered_result . ' Record'.$s;
    }else{
        $s = '';
        $ual_total_results = 'No Records Found';
    }
    // display
    if($al_count_Filtered_result > 0){
        foreach($filter_user_logs_table as $users_logs){
            // custom values
            $format_createdAt = ''.date('F d, Y (D - g:i A)', strtotime($users_logs->created_at));
            $output .= '
            <tr>
                <td class="p12 w35prcnt">'.$format_createdAt.'</td>
                <td>'.$users_logs->act_details.'</td>
            </tr>
        ';
        }
    }else{
        $output .='
            <tr class="no_data_row">
                <td align="center" colspan="7">
                    <div class="no_data_div d-flex justify-content-center align-items-center text-center flex-column">
                        <img class="illustration_svg" src="'. asset('storage/svms/illustrations/no_records_found.svg') .'" alt="no matching users found">
                        <span class="font-italic">No Records Found!
                    </div>
                </td>
            </tr>
        ';
    }
    $ual_paginate .= $filter_user_logs_table->links('pagination::bootstrap-4');
    $data = array(
        'ual_table'            => $output,
        'ual_table_paginate'   => $ual_paginate,
        'ual_total_rows'       => $ual_total_results,
        'ual_total_data_found' => $ual_totalFiltered_result
        );
    
    echo json_encode($data);
}else{
    return view('user_management.user_profile')->with(compact('user_data', 'user_activities', 'user_first_record', 'user_latest_record', 'user_trans_categories'));
}