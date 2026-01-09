import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Sukhumvit Set"',
                    '"Thonburi"',
                    '"SF Pro Text"',
                    '"Helvetica Neue"',
                    'Inter',
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                ios: {
                    blue: '#007AFF',
                    green: '#34C759',
                    red: '#FF3B30',
                    orange: '#FF9500',
                    yellow: '#FFCC00',
                    gray: '#8E8E93',
                    bg: '#F5F5F7',
                    card: '#FFFFFF',
                    border: '#E5E5EA',
                    text: '#1C1C1E',
                    textSec: '#8E8E93',
                },
            },
            boxShadow: {
                'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
                'inner-soft': 'inset 0 2px 4px rgba(0,0,0,0.02)',
                'floating': '0 8px 30px rgba(0,0,0,0.12)',
            },
            borderRadius: {
                'ios': '14px',
            },
            animation: {
                'slide-down': 'slideDown 0.5s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                'fade-out': 'fadeOut 0.3s ease-in forwards',
                'slide-in-right': 'slideInRight 0.4s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                'slide-out-right': 'slideOutRight 0.3s ease-in forwards',
                'pop-in': 'popIn 0.3s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                'slide-up-fade': 'slideUpFade 0.4s cubic-bezier(0.19, 1, 0.22, 1) forwards',
                'slide-down-fade-out': 'slideDownFadeOut 0.3s cubic-bezier(0.19, 1, 0.22, 1) forwards',
            },
            keyframes: {
                slideDown: {
                    '0%': { transform: 'translateY(-100%) scale(0.9)', opacity: '0' },
                    '100%': { transform: 'translateY(0) scale(1)', opacity: '1' },
                },
                fadeOut: {
                    '0%': { opacity: '1', transform: 'scale(1)' },
                    '100%': { opacity: '0', transform: 'scale(0.95)' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                slideOutRight: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(100%)' },
                },
                popIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                slideUpFade: {
                    '0%': { transform: 'translate(-50%, 100%)', opacity: '0' },
                    '100%': { transform: 'translate(-50%, 0)', opacity: '1' },
                },
                slideDownFadeOut: {
                    '0%': { transform: 'translate(-50%, 0)', opacity: '1' },
                    '100%': { transform: 'translate(-50%, 100%)', opacity: '0' },
                },
            },
        },
    },
    plugins: [],
};
