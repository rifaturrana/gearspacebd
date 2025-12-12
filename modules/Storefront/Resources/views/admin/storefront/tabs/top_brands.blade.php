
<!--@php-->
<!--    $brandsForSelect = collect($brands)->pluck('name', 'value')->toArray();-->
<!--@endphp-->
<!--@php-->
<!--    dd([-->
<!--        'brands_type' => gettype($brands),-->
<!--        'brands_content' => $brands,-->
<!--        'setting_value' => setting('storefront_top_brands'),-->
<!--        'setting_type' => gettype(setting('storefront_top_brands'))-->
<!--    ]);-->
<!--@endphp-->
<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('storefront_top_brands_section_enabled', trans('storefront::attributes.section_status'), trans('storefront::storefront.form.enable_brands_section'), $errors, $settings) }}
        {{ Form::select('storefront_top_brands', trans('storefront::attributes.storefront_top_brands'), $errors, $brands, setting(), ['class' => 'selectize prevent-creation', 'multiple' => true]) }}
    </div>
</div>