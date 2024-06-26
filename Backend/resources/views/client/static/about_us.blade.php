@extends('client.master_layout')
@section('content')
    <style>
        .hidden-text {
            clip-path: circle(10% at 0 10%);
            transition: 0.6s ease
        }

        .about-container:hover .hidden-text {
            clip-path: circle(100%);
        }
    </style>
    <div class="w-full h-full">
        {{-- <div class="w-64 h-64 bg-primary-green rounded-full absolute"></div> --}}

        <section
            class=" about-container my-32 mx-5 lg:mx-20  p-4 rounded-3xl  relative border-4 border-primary-green overflow-hidden">
            <div
                class=" hidden-text w-full h-full bg-primary-green absolute flex justify-center items-center top-0 left-0  border-4 border-primary-green p-4 z-20">
                <p class="text-white font-xl text-center">
                {{ __('static.about_desc1') }}
                </br>
                {{ __('static.about_desc2') }}
                </p>
            </div>
            <div class="flex flex-col  items-center my-8">
                <h2 class="font-4xl text-black drop-shadow-lg" style="text-shadow: 2px 3px 3px #f1f1f1">
                {{ __('static.about_desc3') }}
                </h2>
            </div>
            <div class="flex">
                <div class="w-full text-center">
                    {{-- <h1 class="font-4xl logo-font">متاح</h1> --}}
                    <p class="font-lg text-center  pb-10 text-dark-gray ">
                        {{ __('static.about_desc4') }}
                        </br>
                        {{ __('static.about_desc5') }}
            </p>
                </div>

            </div>
        </section>


        <section class="">

            <div class="my-16  flex flex-col items-center justify-center">
                <div class="flex flex-col  items-center my-8">
                    <h2 class="font-4xl"> {{ __('about.team') }} </h2>
                </div>
                <!--end row-->

                <div class="flex flex-wrap justify-center items-center px-5 lg:px-20 p-8 w-full ">

                    <div class="flex flex-col w-screen  relative " id='container'>
                        <div class="flex flex-wrap gap-7 justify-between w-full px-44 ">



                            {{-- team 1 --}}
                            <div class=" relative bubble bubble-4">
                                <div class="absolute p-1 px-4 bg-primary-pink text-black left-20 w-fit">
                                    <h3>
                                      {{ __('static.about_name') }}

                                    </h3>
                                </div>
                                <img class='w-32 h-32' src='/assets/client/images/Avatar (4).png' alt='afnan' />
                                <div class="border-t border-t-gray  mt-5">
                                    <p class='font-xs'>
                                      {{ __('static.about_job') }}
                                    </p>
                                    <a href="javascript:void(0)" class="" style="top: 10px ; left:-10px"><i
                                            class="fab fa-github   p-1 hover:text-black text-gray" title="github">
                                        </i>
                                    </a>
                                    <a href="javascript:void(0)" class="" style="top: 35px ; left:-21px"><i
                                            class="fab fa-linkedin  p-1 hover:text-black text-gray" title="github">
                                        </i>
                                    </a>
                                </div>
                            </div>

                            {{-- team 2 --}}

                        </div>

                        <div class="flex flex-wrap justify-around w-full h-fit  px-44 mt-20">

                            {{-- team 4 --}}

                        </div>

                    </div>
                    <!--end row-->
                </div>

        </section>
    </div>



    <script>
        'use strict';

        for (var i = 1; i <= $('.bubble').length; i++) {
            // var size = Math.random() * 0.4;
            var left = Math.random() * ($('#container').width() - $(this).width());
            var y = Math.random() * ($('#container').height() - $(this).height());
            // var color = "#" + Math.floor(Math.random() * 16777215).toString(16);

            // $('.bubble-' + i) /*.height(size).width(size)*/ .css({
            //     // "position": "absolute",
            //     // "background": color,
            //     "left": Math.random() * ($('#container').width() - $('.bubble-' + i).width()),
            //     "top": Math.random() * ($('#container').height() - $('.bubble-' + i).height()),
            //     // "border-radius": 100
            // })
        }
    </script>
@endsection
