<div class="billing-details">
    <h4 class="section-title">{{ trans('storefront::checkout.billing_details') }}</h4>

    <template x-if="hasAddress">
        <div x-cloak class="address-card-wrap">
            <div class="row">
                <template x-for="address in addresses" :key="address.id">
                    <div class="col d-flex">
                        <address
                            class="address-card"
                            :class="{
                                active: form.billingAddressId === address.id && !form.newBillingAddress,
                                'cursor-default': form.newBillingAddress
                            }"
                            @click="changeBillingAddress(address)"
                        >
                            <svg class="address-card-selected-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.78 9.7L11.11 15.37C10.97 15.51 10.78 15.59 10.58 15.59C10.38 15.59 10.19 15.51 10.05 15.37L7.22 12.54C6.93 12.25 6.93 11.77 7.22 11.48C7.51 11.19 7.99 11.19 8.28 11.48L10.58 13.78L15.72 8.64C16.01 8.35 16.49 8.35 16.78 8.64C17.07 8.93 17.07 9.4 16.78 9.7Z" fill="#292D32"/>
                            </svg>    

                            <template x-if="defaultAddress.address_id === address.id">
                                <span class="badge">
                                    {{ trans('storefront::checkout.default') }}
                                </span>
                            </template>
                            
                            <div class="address-card-data">
                                <span x-text="address.full_name"></span>
                                <span x-text="address.address_1"></span>

                                <template x-if="address.address_2">
                                    <span x-text="address.address_2"></span>
                                </template>

                                <span x-html="`${address.city}, ${address.state_name ?? address.state} ${address.zip}`"></span>
                                <span x-text="address.country_name"></span>
                            </div>
                        </address>
                    </div>
                </template>
            </div>

            <template x-if="!form.newBillingAddress && !form.billingAddressId">
                <span class="error-message">
                    {{ trans('storefront::checkout.you_must_select_an_address') }}
                </span>
            </template>
        </div>
    </template>

    <div x-cloak class="add-new-address-wrap">
        <template x-if="hasAddress">
            <button type="button" class="btn btn-add-new-address" @click="addNewBillingAddress">
                <span x-text="form.newBillingAddress ? '-' : '+'"></span>
                
                {{ trans('storefront::checkout.add_new_address') }}
            </button>
        </template>

        <div class="add-new-address-form" x-show="!hasAddress || form.newBillingAddress">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="billing-first-name">
                            {{ trans('checkout::attributes.billing.first_name') }}<span>*</span>
                        </label>

                        <input
                            type="text"
                            name="billing[first_name]"
                            id="billing-first-name"
                            class="form-control"
                            x-model="form.billing.first_name"
                        >

                        <template x-if="errors.has('billing.first_name')">
                            <span class="error-message" x-text="errors.get('billing.first_name')"></span>
                        </template>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label for="billing-last-name">
                            {{ trans('checkout::attributes.billing.last_name') }}<span>*</span>
                        </label>

                        <input
                            type="text"
                            name="billing[last_name]"
                            id="billing-last-name"
                            class="form-control"
                            x-model="form.billing.last_name"
                        >

                        <template x-if="errors.has('billing.last_name')">
                            <span class="error-message" x-text="errors.get('billing.last_name')"></span>
                        </template>
                    </div>
                </div>

                <div class="col-md-18">
                    <div class="form-group">
                        <label for="billing-address-1">
                            {{ trans('checkout::attributes.street_address') }}<span>*</span>
                        </label>

                        <input
                            type="text"
                            name="billing[address_1]"
                            id="billing-address-1"
                            class="form-control"
                            placeholder="{{ trans('checkout::attributes.billing.address_1') }}"
                            x-model="form.billing.address_1"
                        >

                        <template x-if="errors.has('billing.address_1')">
                            <span class="error-message" x-text="errors.get('billing.address_1')"></span>
                        </template>
                    </div>

                    <div class="form-group">
                        <input
                            type="text"
                            name="billing[address_2]"
                            class="form-control"
                            placeholder="{{ trans('checkout::attributes.billing.address_2') }}"
                            x-model="form.billing.address_2"
                        >
                    </div>
                </div>

                <!-- <div class="col-md-9">
                    <div class="form-group">
                        <label for="billing-city">
                            {{ trans('checkout::attributes.billing.city') }}<span>*</span>
                        </label>

                        <input
                            type="text"
                            name="billing[city]"
                            :value="form.billing.city"
                            id="billing-city"
                            class="form-control"
                            @change="changeBillingCity($event.target.value)"
                        >

                        <template x-if="errors.has('billing.city')">
                            <span class="error-message" x-text="errors.get('billing.city')"></span>
                        </template>
                    </div>
                </div> -->

                <!-- <div class="col-md-9">
                    <div class="form-group">
                        <label for="billing-zip">
                            {{ trans('checkout::attributes.billing.zip') }}<span>*</span>
                        </label>

                        <input
                            type="text"
                            name="billing[zip]"
                            :value="form.billing.zip"
                            id="billing-zip"
                            class="form-control"
                            @change="changeBillingZip($event.target.value)"
                        >

                        <template x-if="errors.has('billing.zip')">
                            <span class="error-message" x-text="errors.get('billing.zip')"></span>
                        </template>
                    </div>
                </div> -->

                <!-- <div class="col-md-9">
                    <div class="form-group">
                        <label for="billing-country">
                            {{ trans('checkout::attributes.billing.country') }}<span>*</span>
                        </label>

                        <select
                            name="billing[country]"
                            id="billing-country"
                            class="form-control arrow-black"
                            @change="changeBillingCountry($event.target.value)"
                        >
                            <option value="">{{ trans('storefront::checkout.please_select') }}</option>
                            
                            <template x-for="(name, code) in countries" :key="code">
                                <option :value="code" x-text="name"></option>
                            </template>
                        </select>

                        <template x-if="errors.has('billing.country')">
                            <span class="error-message" x-text="errors.get('billing.country')"></span>
                        </template>
                    </div>
                </div> -->

                
                <div class="col-md-9">
    <div class="form-group">
        <label for="billing-state">
            {{ __('District') }}<span>*</span>
        </label>

        <select
            name="billing[state]"
            id="billing-state"
            class="form-control"
            x-model="form.billing.state"
            @change="changeBillingState($event.target.value)"
        >
            <option value="">{{ __('Select District') }}</option>
            <option value="BAG">Bagerhat</option>
            <option value="BAN">Bandarban</option>
            <option value="BAR">Barguna</option>
            <option value="BARI">Barisal</option>
            <option value="BHO">Bhola</option>
            <option value="BOG">Bogra</option>
            <option value="BRA">Brahmanbaria</option>
            <option value="CHA">Chandpur</option>
            <option value="CHI">Chittagong</option>
            <option value="CHU">Chuadanga</option>
            <option value="COM">Comilla</option>
            <option value="COX">Cox's Bazar</option>
            <option value="DHA">Dhaka</option>
            <option value="DIN">Dinajpur</option>
            <option value="FAR">Faridpur</option>
            <option value="FEN">Feni</option>
            <option value="GAI">Gaibandha</option>
            <option value="GAZI">Gazipur</option>
            <option value="GOP">Gopalganj</option>
            <option value="HAB">Habiganj</option>
            <option value="JAM">Jamalpur</option>
            <option value="JES">Jessore</option>
            <option value="JHA">Jhalokati</option>
            <option value="JHE">Jhenaidah</option>
            <option value="JOY">Joypurhat</option>
            <option value="KHA">Khagrachhari</option>
            <option value="KHU">Khulna</option>
            <option value="KIS">Kishoreganj</option>
            <option value="KUR">Kurigram</option>
            <option value="KUS">Kushtia</option>
            <option value="LAK">Lakshmipur</option>
            <option value="LAL">Lalmonirhat</option>
            <option value="MAD">Madaripur</option>
            <option value="MAG">Magura</option>
            <option value="MAN">Manikganj</option>
            <option value="MEH">Meherpur</option>
            <option value="MOU">Moulvibazar</option>
            <option value="MUN">Munshiganj</option>
            <option value="MYM">Mymensingh</option>
            <option value="NAO">Naogaon</option>
            <option value="NAR">Narail</option>
            <option value="NARG">Narayanganj</option>
            <option value="NARD">Narsingdi</option>
            <option value="NAT">Natore</option>
            <option value="NAW">Nawabganj</option>
            <option value="NET">Netrakona</option>
            <option value="NIL">Nilphamari</option>
            <option value="NOA">Noakhali</option>
            <option value="PAB">Pabna</option>
            <option value="PAN">Panchagarh</option>
            <option value="PAT">Patuakhali</option>
            <option value="PIR">Pirojpur</option>
            <option value="RAJB">Rajbari</option>
            <option value="RAJ">Rajshahi</option>
            <option value="RAN">Rangamati</option>
            <option value="RANP">Rangpur</option>
            <option value="SAT">Satkhira</option>
            <option value="SHA">Shariatpur</option>
            <option value="SHE">Sherpur</option>
            <option value="SIR">Sirajganj</option>
            <option value="SUN">Sunamganj</option>
            <option value="SYL">Sylhet</option>
            <option value="TAN">Tangail</option>
            <option value="THA">Thakurgaon</option>
        </select>

        <template x-if="errors.has('billing.state')">
            <span class="error-message" x-text="errors.get('billing.state')"></span>
        </template>
    </div>
</div>


            </div>
        </div>
    </div>
</div>
