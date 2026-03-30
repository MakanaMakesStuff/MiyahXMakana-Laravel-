import type { AppLayoutProps } from '@/types';

export default function ChatMessengerLayout({ children }: AppLayoutProps) {
    return (
        <div className="flex min-h-screen w-full flex-col bg-white text-gray-950 antialiased">
            {children}
        </div>
    );
}
