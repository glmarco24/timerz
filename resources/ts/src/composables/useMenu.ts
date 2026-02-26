export interface MenuItem {
  label: string;
  to: string;
  icon?: string;
  bottom?: boolean;
}

export function useMenu(): MenuItem[] {
  return [
    { label: 'Overview', to: '/', icon: 'home' },
    { label: 'Time', to: '/companies', icon: 'schedule' },
    { label: 'Calendar', to: '/calendar', icon: 'event' },
    { label: 'Money', to: '/money', icon: 'payments' },
    { label: 'Book', to: '/book', icon: 'book' },
    { label: 'Profile', to: '/profile', icon: 'account_circle', bottom: true },
  ];
}
