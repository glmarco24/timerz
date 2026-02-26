export interface MenuItem {
  label: string;
  to: string;
  icon?: string;
  bottom?: boolean;
}

export function useMenu(): MenuItem[] {
  return [
    { label: 'Overview', to: '/', icon: 'home' },
    { label: 'My companies', to: '/companies', icon: 'apartment' },
    { label: 'Profile', to: '/profile', icon: 'account_circle', bottom: true },
  ];
}

