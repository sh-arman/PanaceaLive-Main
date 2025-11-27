<footer class="footer">
    <div class="container">
        <div class="row" id="kumfooter">
            <div class="col text-center">
                <a href="http://www.kumarika.com.bd/" class="item mx-2" target="_blank">
                    <img class="icon" src="{{ asset('livecheckpro/asset/shopping_cart.svg') }}">
                    <p>{{ trans('literature.footer-order') }}<br>{{ trans('literature.footer-online') }}</p>
                </a>
                <a href="https://www.facebook.com/kumarika.care" class="item mx-2" target="_blank">
                    <img class=" icon" src="{{ asset('livecheckpro/asset/facebook.svg') }}">
                    <p>{{ trans('literature.footer-fb') }}<br>{{ trans('literature.footer-page') }}</p>
                </a>
                {{-- <a href="{{asset('livecheckpro/leaflets/mups20/Maxpro_MUPS_20_Insert.pdf')}}" class="item mx-2" target="_blank" rel="noopener noreferrer">
                    <img class=" icon" src="{{ asset('livecheckpro/asset/leaflet.svg') }}">
                    <p>{{ trans('literature.footer-medicine') }}<br>{{ trans('literature.footer-leaflet') }}</p>
                </a> --}}
            </div>
        </div>
    </div>
</footer>