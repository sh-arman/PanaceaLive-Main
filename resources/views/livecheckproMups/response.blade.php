<style>
.btnverify {
    font-size: 16px;
    font-weight: 600;
    color: #000000;
    text-align: center;
    width: 120px;
    padding: 8px;
    border: 0px;
    border-radius: 100px;
    background-color: #fc924c;
    /* background: #FC924C; */
    /* background-color: linear-gradient(356.3deg, #FC924C 31.69%, #FFFFFF 208.06%); */
}

.mark {
    width: 30%;
    height: auto;
    background-color: transparent;
    padding-bottom: 1rem;
}

.live-check {
    width: 40%;
    height: auto;
    background-color: transparent;
    padding-bottom: 1rem;
}

.info p {
    font-size: 15px !important;
    font-weight: 500;
    line-height: 0.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 1.3rem 0rem !important;
}

.expire {
    margin: 0 auto !important;
    padding: 10px;
    width: 50%;
    border-radius: 10px;
    background-color: #fee93b;
}
</style>

<div class="modal container fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
           <div class="modal-body" style="padding : 0 !important;">
              <div class="content-box">
                @if($response)
                    @if ($response['status'] == 'invalid code')
                      
                        <img class="mark" src="{{ asset('livecheckpro/asset/incorrect.svg') }}">
                        <h4>Wrong Code</h4>
                        <p>Please try with right 7 digit code</p>

                    @elseif ($response['status'] == 'already verified')

                        <img class="mark" src="{{ asset('livecheckpro/asset/tick.svg') }}">
                        <h4>This Medicine Is Verified</h4>
                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">Manufacturer: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">Medicine Name: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">Manufacturering Date: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">Expiry Date: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p>
                        </div>

                        <div id="warningMsg">
                          <div class="warning">
                            <p style="font-size: .9rem !important;">This medicine was verified previously. If this medicine was verified by you or your family member or the
                            pharmacist/chemist, you may use it.</p>
                            <img src="{{ asset('livecheckpro/asset/warning.svg') }}">
                          </div>

                          <div class="info">
                            <p id="totalCount">
                              <span class="bold-title">Previously Verified By : </span> &nbsp;  {{ $response['info']['preNumber'] }}
                            </p>
                            <p id="totalCount">
                              <span class="bold-title">Previous Verification Date: </span> &nbsp;  {{ $response['info']['preDate'] }}
                            </p>
                            <p id="totalCount">
                              <span class="bold-title">Total Number Of Verification: </span> &nbsp;  {{ $response['info']['totalCount'] }}
                            </p>
                          </div>
                        </div>

                    @elseif ($response['status'] == 'verified first time')

                        <img class="mark" src="{{ asset('livecheckpro/asset/tick.svg') }}">
                        <h4>Verified</h4>
                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">Manufacturer: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">Medicine Name: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">Manufacturering Date: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">Expiry Date: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p>
                        </div>
                    
                    @elseif ($response['status'] == 'expired')

                        <img class="mark" style="width: 15%;" src="{{ asset('livecheckpro/asset/warning.svg') }}">
                        <h4>This Medicine Is Expired</h4>
                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">Manufacturer: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">Medicine Name: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">Manufacturering Date: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">Expiry Date: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p>
                        </div>


                        <div class="mb-3">
                            <p class="expire" style="font-size: 1rem !important; font-weight: bold;">Do Not Use This Medicine</p>
                        </div>
                    

                    @elseif ($response['status'] == 'wrong number')
                        <img class="mark" src="{{ asset('livecheckpro/asset/incorrect.svg') }}">
                        <h4>Invalid Phone Number</h4>
                        <p>Please try with your correct 11 digit's valid phone number eg. 019474***47</p>
                    @else 
                        <h4>Invalid Code</h4>
                  @endif
                @endif
              </div>
          </div>
          <div class="row justify-content-center mb-4">
            <a href="{{ route('mups') }}">
              <button type="submit" id="donebtn" class="btnverify" >Done</button>
            </a>
          </div>
        </div>
    </div>
</div>

