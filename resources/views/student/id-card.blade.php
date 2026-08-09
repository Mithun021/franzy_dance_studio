@extends('partials.master')

@section('title','Student ID Card')

@section('content')

<style>
    /*=====================================================
            GOOGLE FONT
======================================================*/

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/*=====================================================
                RESET
======================================================*/

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    background:#edf3fb;

    font-family:'Poppins',sans-serif;

}

/*=====================================================
                PAGE
======================================================*/

.id-wrapper{

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    padding:40px 20px;

}

/*=====================================================
                CARD
======================================================*/

.id-card{

    width:355px;

    height:550px;

    background:#fff;

    border-radius:22px;

    overflow:hidden;

    display:flex;

    position:relative;

    box-shadow:
        0 25px 60px rgba(0,0,0,.18),
        0 8px 20px rgba(13,116,209,.12);

    transition:.35s ease;

}

.id-card:hover{

    transform:translateY(-8px);

    box-shadow:
        0 40px 80px rgba(0,0,0,.20),
        0 15px 35px rgba(13,116,209,.15);

}

/*=====================================================
            LEFT STRIP
======================================================*/

.left-strip{

    width:72px;

    background:linear-gradient(
        180deg,
        #1685ff 0%,
        #0d66d8 40%,
        #084fae 100%
    );

    position:relative;

    display:flex;

    justify-content:center;

    align-items:center;

    overflow:hidden;

}

/* Decorative circles */

.left-strip::before{

    content:"";

    position:absolute;

    width:180px;

    height:180px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-90px;

    left:-90px;

}

.left-strip::after{

    content:"";

    position:absolute;

    width:150px;

    height:150px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    bottom:-70px;

    right:-60px;

}

/* Extra Pattern */

.strip-pattern{

    position:absolute;

    inset:0;

    background-image:

    repeating-linear-gradient(

        45deg,

        rgba(255,255,255,.04),

        rgba(255,255,255,.04) 8px,

        transparent 8px,

        transparent 16px

    );

}

/*=====================================================
        VERTICAL COMPANY NAME
======================================================*/

.vertical-company{

    position:relative;

    z-index:5;

    writing-mode:vertical-rl;

    transform:rotate(180deg);

    white-space:nowrap;

    color:#fff;

    font-size:16px;

    font-weight:700;

    letter-spacing:5px;

    text-transform:uppercase;

}

/*=====================================================
            RIGHT SIDE
======================================================*/

.right-side{

    flex:1;

    position:relative;

    display:flex;

    flex-direction:column;

    align-items:center;

    padding:22px 18px 18px;

}

/*=====================================================
            TOP HEADER
======================================================*/

.top-header{

    position:absolute;

    top:0;

    left:0;

    right:0;

    height:110px;

    background:linear-gradient(
        90deg,
        #1685ff,
        #0d66d8
    );

    border-bottom-left-radius:55px;

}

.header-wave{

    position:absolute;

    bottom:-25px;

    right:-20px;

    width:160px;

    height:70px;

    background:rgba(255,255,255,.15);

    border-radius:50px;

    transform:rotate(-12deg);

}

/*=====================================================
                LOGO
======================================================*/

.logo-section{

    position:relative;

    z-index:5;

    margin-top:8px;

    display:flex;

    flex-direction:column;

    align-items:center;

}

.company-logo{

    width:62px;

    height:62px;

    object-fit:cover;

    background:#fff;

    border-radius:50%;

    padding:5px;

    box-shadow:0 8px 20px rgba(0,0,0,.18);

}

.logo-placeholder{

    width:62px;

    height:62px;

    background:#fff;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    font-weight:700;

    color:#0d66d8;

    box-shadow:0 8px 20px rgba(0,0,0,.15);

}

.logo-section h2{

    margin-top:10px;

    color:#ffffff;

    font-size:22px;

    font-weight:700;

    letter-spacing:2px;

}

.logo-section p{

    color:#eef6ff;

    font-size:11px;

    letter-spacing:3px;

    font-weight:500;

}

/*=====================================================
                PHOTO
======================================================*/

.photo-section{

    margin-top:28px;

    position:relative;

    z-index:5;

}

.photo-ring{

    width:132px;

    height:132px;

    border-radius:50%;

    background:linear-gradient(
        135deg,
        #ffffff,
        #d9ebff
    );

    padding:5px;

    box-shadow:
        0 12px 25px rgba(13,116,209,.22);

}

.student-photo{

    width:100%;

    height:100%;

    object-fit:cover;

    border-radius:50%;

    border:4px solid #fff;

}

.photo-placeholder{

    width:100%;

    height:100%;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    background:#eef5fc;

    color:#94a3b8;

    border:4px solid #fff;

}

/*=====================================================
            STUDENT INFO
======================================================*/

.student-info{

    margin-top:20px;

    text-align:center;

}

.student-info h1{

    font-size:23px;

    color:#17365d;

    font-weight:700;

    letter-spacing:.5px;

    text-transform:uppercase;

}

.student-badge{

    display:inline-block;

    margin-top:10px;

    padding:6px 20px;

    border-radius:40px;

    background:linear-gradient(
        90deg,
        #1685ff,
        #0d66d8
    );

    color:#fff;

    font-size:11px;

    font-weight:700;

    letter-spacing:2px;

    box-shadow:0 8px 18px rgba(13,116,209,.25);

}

/*=====================================================
                DETAILS SECTION
======================================================*/

.details-section{

    width:100%;

    margin-top:22px;

    display:flex;

    flex-direction:column;

    gap:10px;

}

.detail-box{

    display:flex;

    justify-content:space-between;

    align-items:center;

    background:#f8fbff;

    border:1px solid #e3eefc;

    border-left:4px solid #0d66d8;

    border-radius:10px;

    padding:10px 12px;

    transition:.3s;

}

.detail-box:hover{

    background:#eef6ff;

    transform:translateX(3px);

}

.detail-box .label{

    font-size:11px;

    font-weight:700;

    color:#64748b;

    text-transform:uppercase;

    letter-spacing:1px;

}

.detail-box .value{

    font-size:13px;

    font-weight:600;

    color:#17365d;

    text-align:right;

    max-width:170px;

    word-break:break-word;

}

/*=====================================================
                BOTTOM SECTION
======================================================*/

.bottom-section{

    margin-top:18px;

    width:100%;

    display:flex;

    justify-content:space-between;

    align-items:flex-end;

}

/*=====================
        QR
======================*/

.qr-area{

    display:flex;

    flex-direction:column;

    align-items:center;

}

.qr-box{

    width:58px;

    height:58px;

    border:2px dashed #0d66d8;

    border-radius:8px;

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:12px;

    font-weight:700;

    color:#0d66d8;

    background:#fff;

}

.qr-area small{

    margin-top:6px;

    color:#64748b;

    font-size:10px;

    letter-spacing:1px;

}

/*=====================
    SIGNATURE
======================*/

.sign-area{

    width:120px;

    text-align:center;

}

.sign-line{

    border-bottom:2px solid #17365d;

    margin-bottom:6px;

}

.sign-area span{

    font-size:10px;

    color:#64748b;

    font-weight:600;

    letter-spacing:1px;

}

/*=====================================================
                    FOOTER
======================================================*/

.footer{

    width:100%;

    margin-top:auto;

    background:linear-gradient(
        90deg,
        #17365d,
        #0d66d8
    );

    color:#fff;

    border-radius:12px;

    padding:10px;

    text-align:center;

}

.footer-title{

    font-size:11px;

    font-weight:700;

    letter-spacing:2px;

}

.footer-address{

    margin-top:3px;

    font-size:9px;

    opacity:.9;

    letter-spacing:1px;

}

/*=====================================================
                SCROLL SAFE
======================================================*/

img{

    max-width:100%;

    display:block;

}

/*=====================================================
                RESPONSIVE
======================================================*/

@media(max-width:480px){

    .id-wrapper{

        padding:20px 10px;

    }

    .id-card{

        width:100%;

        max-width:355px;

    }

}

/*=====================================================
                PRINT
======================================================*/

@page{

    size:86mm 54mm;

    margin:0;

}

@media print{

    *{

        -webkit-print-color-adjust:exact !important;

        print-color-adjust:exact !important;

    }

    body{

        background:#fff;

    }

    body *{

        visibility:hidden;

    }

    .id-card,
    .id-card *{

        visibility:visible;

    }

    .id-card{

        position:absolute;

        left:0;

        top:0;

        width:86mm;

        height:54mm;

        margin:0;

        border-radius:0;

        box-shadow:none;

        transform:none;

    }

    .id-wrapper{

        padding:0;

        margin:0;

        display:block;

    }

}

/*=====================================================
                NICE ANIMATION
======================================================*/

.company-logo,
.photo-ring,
.student-badge{

    transition:.35s;

}

.id-card:hover .company-logo{

    transform:rotate(8deg);

}

.id-card:hover .photo-ring{

    transform:scale(1.03);

}

.id-card:hover .student-badge{

    letter-spacing:3px;

}
</style>

@php
    $course = $course ?? null;
@endphp

<div class="id-wrapper">

    <div class="id-card">

        <!--========================
            LEFT STRIP
        =========================-->

        <div class="left-strip">

            <div class="strip-pattern"></div>

            <div class="vertical-company">

                FRENZY DANCE STUDIO

            </div>

        </div>


        <!--========================
            RIGHT SIDE
        =========================-->

        <div class="right-side">

            <!--========================
                TOP HEADER
            =========================-->

            <div class="top-header">

                <div class="header-wave"></div>

            </div>


            <!--========================
                LOGO
            =========================-->

            {{-- <div class="logo-section">

                @if(!empty($setting->logo))

                    <img
                        src="{{ asset('storage/'.$setting->logo) }}"
                        class="company-logo"
                        alt="Logo">

                @else

                    <div class="logo-placeholder">

                        LOGO

                    </div>

                @endif

                <h2>

                    FRENZY

                </h2>

                <p>

                    DANCE STUDIO

                </p>

            </div> --}}



            <!--========================
                STUDENT PHOTO
            =========================-->

            <div class="photo-section">

                <div class="photo-ring">

                    @if($student->profile_image)

                        <img
                            src="{{ asset('storage/'.$student->profile_image) }}"
                            class="student-photo">

                    @else

                        <div class="photo-placeholder">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="55"
                                 height="55"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.8"
                                      d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>

                            </svg>

                        </div>

                    @endif

                </div>

            </div>



            <!--========================
                STUDENT NAME
            =========================-->

            <div class="student-info">

                <h1>

                    {{ strtoupper($student->name) }}

                </h1>

                <span class="student-badge">

                    STUDENT

                </span>

            </div>
                        <!--=================================
                    DETAILS SECTION
            ==================================-->

            <div class="details-section">

                <div class="detail-box">

                    <div class="label">
                        Student ID
                    </div>

                    <div class="value">
                        {{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
                    </div>

                </div>

                <div class="detail-box">

                    <div class="label">
                        Phone
                    </div>

                    <div class="value">
                        {{ $student->phone ?? '-' }}
                    </div>

                </div>

                <div class="detail-box">

                    <div class="label">
                        DOB
                    </div>

                    <div class="value">

                        @if($student->date_of_birth)

                            {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }}

                        @else

                            -

                        @endif

                    </div>

                </div>

            </div>



            <!--=================================
                    FOOTER
            ==================================-->

            <div class="footer">

                <div class="footer-title">

                    LEARN • DANCE • PERFORM

                </div>

                <div class="footer-address">

                    www.frenzydancestudio.com

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
