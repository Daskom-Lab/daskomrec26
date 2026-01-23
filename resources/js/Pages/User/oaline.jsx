import { useState } from 'react';
import { Head, router } from '@inertiajs/react';

import ButtonSidebar from '@components/ButtonSidebar';
import ButtonHome from '@components/ButtonHome';
import UserSidebar from '@components/UserSidebar';
import UnderwaterEffect from '@components/UnderwaterEffect';

import utama from '@assets/backgrounds/utama.png';
import logoImg from '@assets/logo/ORB_DLOR 1.png';
import PCboard from '@assets/backgrounds/01-ABoard_PC.png';
import Mobileboard from '@assets/backgrounds/02-ABoard_Mobile.png';
import qrCodeImg from '@assets/logo/Code.jpeg';

export default function OaLinePage() {
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);

    const lineInfo = {
        id: "@492ehaee",
        link: "https://line.me/R/ti/p/%40492ehaee" 
    };

    return (
        <>
            <Head title="OA Line Information" />
            <div className="relative w-full h-screen overflow-hidden text-white font-caudex">

                {/* --- NAVIGATION --- */}
                <div className="absolute top-4 left-4 md:top-6 md:left-6 z-50">
                    <ButtonSidebar onClick={() => setIsSidebarOpen(true)} />
                </div>

                <div className="absolute top-4 right-4 md:top-6 md:right-6 z-50">
                    <ButtonHome onClick={() => router.visit('/user/home')} />
                </div>

                {/* --- BACKGROUND --- */}
                <div className="absolute inset-0 z-0">
                    <img
                        src={utama}
                        alt="Background Utama"
                        className="w-full h-full object-cover brightness-75"
                    />
                    <div className="absolute inset-0 bg-gradient-to-b from-cyan-900/20 via-transparent to-slate-900/80" />
                </div>

                <div className="absolute inset-0 z-10 pointer-events-none">
                    <UnderwaterEffect />
                </div>

                {/* --- MAIN CONTENT BOARD --- */}
                <div className="absolute inset-0 z-40 flex items-center justify-center p-4">
                    
                    {/* Container Board Responsif */}
                    <div className="relative w-[85vw] aspect-[3/4] md:w-auto md:h-[85vh] md:aspect-[4/3] max-w-[1000px] flex flex-col items-center justify-center animate-popup">
                        
                        {/* Frame Images */}
                        <img
                            src={Mobileboard}
                            alt="Frame Mobile"
                            className="block md:hidden absolute inset-0 w-full h-full object-contain pointer-events-none drop-shadow-2xl"
                        />
                        <img
                            src={PCboard}
                            alt="Frame PC"
                            className="hidden md:block absolute inset-0 w-full h-full object-contain pointer-events-none drop-shadow-2xl"
                        />

                        {/* Content Safe Zone */}
                        <div className="absolute inset-0 flex flex-col items-center justify-center text-center 
                                        pt-[28%] pb-[22%] px-[14%] 
                                        md:pt-[22%] md:pb-[18%] md:px-[18%]">
                            
                            <h2 className="text-xl sm:text-2xl md:text-4xl font-bold mb-3 md:mb-6 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] leading-tight">
                                Official Account Line
                            </h2>

                            <div className="flex flex-col md:flex-row items-center justify-center gap-3 md:gap-8 w-full h-full max-h-[70%]">
                                
                                {/* QR Code Area */}
                                <div className="shrink-0 bg-white p-2 rounded-xl shadow-lg rotate-0 md:-rotate-2 transition-transform hover:rotate-0 duration-300">
                                    <img 
                                        src={qrCodeImg} 
                                        alt="QR Code Line" 
                                        className="w-24 h-24 sm:w-32 sm:h-32 md:w-50 md:h-50 object-contain"
                                    />
                                </div>

                                {/* Text Info Area */}
                                <div className="flex flex-col items-center md:items-start space-y-2 md:space-y-4 max-w-full">
                                    
                                    <div className="flex flex-col items-center md:items-start">
                                        <span className="text-[10px] md:text-sm text-white uppercase tracking-widest font-bold">
                                            ID Line
                                        </span>
                                        <p className="text-lg sm:text-xl md:text-3xl font-bold drop-shadow-md select-all tracking-wide">
                                            {lineInfo.id}
                                        </p>
                                    </div>

                                    <a 
                                        href={lineInfo.link}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="bg-[#0C365B] hover:brightness-110 text-white px-5 py-2 md:px-6 md:py-2.5 rounded-full font-bold shadow-lg 
                                                   transition-all active:scale-95 flex items-center gap-2 text-xs sm:text-sm md:text-base mt-1"
                                    >
                                        <span>Add Friend</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor" className="w-3 h-3 md:w-4 md:h-4">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {/* Logo DLOR */}
                        <div className="absolute bottom-[18%] right-[15%] md:bottom-[22%] md:right-[20%] pointer-events-none">
                            <img 
                                src={logoImg} 
                                alt="DLOR Logo" 
                                className="w-10 h-10 md:w-20 md:h-20 object-contain drop-shadow-md"
                            />
                        </div>

                    </div>
                </div>

                {/* --- SIDEBAR --- */}
                <UserSidebar
                    isOpen={isSidebarOpen}
                    onClose={() => setIsSidebarOpen(false)}
                    onLogout={() => router.post('/logout')} 
                />

            </div>
        </>
    );
}