import React from 'react';
import { useMusic } from './SoundProvider';

export default function MuteButton() {
    const { toggleMute, muted } = useMusic();

    const atlantisBlue = "#00fbff";

    return (
        <button
            onClick={toggleMute}
            className={`
                fixed bottom-[30px] right-[40px] z-[9999]
                w-[60px] h-[60px] rounded-full
                flex items-center justify-center
                cursor-pointer
                border-[3px] border-[#05253e]
                transition-all duration-300 ease-in-out
                bg-[linear-gradient(145deg,#52b7c3,#1e6883)]
                ${muted
                    ? "shadow-[0_0_10px_rgba(0,0,0,0.5)]"
                    : "shadow-[0_0_20px_#00fbff,inset_0_0_10px_rgba(255,255,255,0.5)]"
                }
                hover:scale-110 hover:rotate-[5deg]
            `}
        >
            {muted ? <MutedTrident /> : <ActiveTrident color={atlantisBlue} />}
        </button>
    );
}

function ActiveTrident({ color }) {
    return (
        <svg
            width="32"
            height="32"
            viewBox="0 0 24 24"
            fill="none"
            style={{ filter: `drop-shadow(0 0 2px ${color})` }}
        >
            <path
                d="M12 2V12M12 12H10M12 12H14M7 4V10C7 12.7614 9.23858 15 12 15C14.7614 15 17 12.7614 17 10V4"
                stroke="#4a3b00"
                strokeWidth="1.5"
                strokeLinecap="round"
            />
            <path
                d="M12 15V22"
                stroke="#4a3b00"
                strokeWidth="1.5"
                strokeLinecap="round"
            />
        </svg>
    );
}

function MutedTrident() {
    return (
        <svg
            width="32"
            height="32"
            viewBox="0 0 24 24"
            fill="none"
            className="opacity-60"
        >
            <path
                d="M12 2V12M7 6V10C7 12.7614 9.23858 15 12 15C14.7614 15 17 12.7614 17 10V8"
                stroke="#4a3b00"
                strokeWidth="1.5"
                strokeLinecap="round"
            />
            <path
                d="M12 15V22"
                stroke="#4a3b00"
                strokeWidth="1.5"
                strokeLinecap="round"
            />
            <line
                x1="5"
                y1="5"
                x2="19"
                y2="19"
                stroke="#7e0000"
                strokeWidth="2"
                strokeLinecap="round"
            />
        </svg>
    );
}
