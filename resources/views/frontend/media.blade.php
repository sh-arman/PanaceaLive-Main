@extends('layouts.front')

<div class="fh-press">

@section('content')

        <div class="press">
            <div class="container">
                <div class="row">
                    <h2 class="text-center">Press</h2>
                    <div class="col-md-6 col-md-offset-3">
                        <a style="color: #000000" href="https://www.thedailystar.net/frontpage/know-your-medicine-1243567" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="https://assetsds.cdnedge.bluemix.net/sites/all/themes/tds/logo.png" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                    Know your medicine | Send text to verify if the medicine is counterfeit or not.
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                        <a style="color: #000000" href="http://www.kalerkantho.com/print-edition/last-page/2017/01/29/457543" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="{{asset('frontend/images/media/kalerkantho.png')}}" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                        তাঁদের উদ্ভাবন করা প্রযুক্তি এখন দেশের ওষুধ খাতে একরকম বিস্ময় সৃষ্টিকারী।
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                        <a style="color: #000000" href="http://goo.gl/wM4dgU" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="{{asset('frontend/images/media/prothom_alo.png')}}" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                        নকল ওষুধ শনাক্ত করতে কাজ করছেন দুই সহোদর।
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                        <a style="color: #000000" href="http://www.ittefaq.com.bd/print-edition/drishtikon/2014/09/26/6030.html" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="{{asset('frontend/images/media/mainlogo.png')}}" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                        বাংলাদেশেরই কিছু তরুণ উদ্ভাবক নিয়ে আসছে নকল ওষুধের হাত থেকে রক্ষা পাওয়ার সহজ সমাধান-প্যানাসিয়া।
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                        <a style="color: #000000" href="http://causeartist.com/10-best-presentations-1-million-cups-far/" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="https://thursdayinterview.files.wordpress.com/2016/04/causeartist_logo-1-2.png?w=645" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                        Panacea helps consumers verify the authenticity of that company’s medicines.
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                        <a style="color: #000000" href="http://www.cnbc.com/2013/11/18/tups.html?page=18" target="_blank">
                            <div class="row row-press-post">
                                <div class="col-md-4">
                                    <img src="{{asset('frontend/images/media/CNBC_Logo_Line.jpg')}}" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <blockquote>
                                        One of the world's hottest startups.
                                    </blockquote>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection

@section('scripts')
    @parent

@endsection
