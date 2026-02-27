export interface MenuItem {
  label: string;
  to: string;
  icon?: string;
  bottom?: boolean;
}

export function useMenu(): MenuItem[] {
  return [
    { label: 'Overview', to: '/', icon: 'home' },
    { label: 'Time', to: '/time', icon: 'schedule' },
    { label: 'Calendar', to: '#', icon: 'event' },
    { label: 'Money', to: '#', icon: 'payments' },
    { label: 'Book', to: '#', icon: 'book' },
    { label: 'Profile', to: '/profile', icon: 'account_circle', bottom: true },
  ];
}
