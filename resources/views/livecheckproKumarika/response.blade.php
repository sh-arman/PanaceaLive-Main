<div class="content-box" id="responseDiv" style="display: none">

  <h6 id="wrongHeading" style="display: none">{{ trans('literature.non-verified-heading-kum') }}</h6>
  <span id="wrongInfoMsg" style="display: none">{{ trans('literature.non-verified-sub-heading') }}</span>

  <h6 id="verifiedHeading">{{trans('literature.verified-heading-kum')}}</h6>
  <div class="info" id="verifiedInfo">
    <p id="manufacturer"><span class="bold-title">
        {{trans('literature.info-Manufacturer')}}:
      </span>
      &nbsp;
    </p>
    <p id="productDosage"><span class="bold-title">
        {{trans('literature.info-Product-Name')}}:
      </span>
      &nbsp;
    </p>
    <p id="mfg"><span class="bold-title">
        {{trans('literature.info-Manufacturing-Date')}}:
      </span>
      &nbsp;
    </p>
    <p id="expiry"><span class="bold-title">
        {{trans('literature.info-Expiry-Date')}}:
      </span>
      &nbsp;
    </p>
    <p id="batch"><span class="bold-title">
        {{trans('literature.info-Batch-No')}}:
      </span>
      &nbsp;
    </p>
  </div>

  <div id="warningMsg" style="display: none">
    <div class="warning">
      <p>{{ trans('literature.warning-paragraph-kum') }}</p>
      <img src="{{ asset('livecheckpro/asset/warning.svg') }}">
    </div>
    <div class="info">
      <p id="preNumber">{{ trans('literature.previous-number') }}: </p>
      <p id="preDate">{{ trans('literature.auth-date') }}: </p>
      <p id="totalCount" style="display: none">{{ trans('literature.verification-count') }}: </p>
    </div>
  </div>
</div>