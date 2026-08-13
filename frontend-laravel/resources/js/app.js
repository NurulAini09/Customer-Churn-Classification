import Alpine from 'alpinejs';
import 'flowbite';
import {
  Activity,
  ArrowRight,
  BrainCircuit,
  Camera,
  CirclePlay,
  Cpu,
  createIcons,
  ExternalLink,
  Files,
  Gauge,
  History,
  ImageOff,
  Info,
  LayoutDashboard,
  Lock,
  LogIn,
  LogOut,
  Mail,
  Moon,
  Plus,
  RotateCcw,
  Send,
  ShieldCheck,
  Sparkles,
  Siren,
  Sun,
  Trash2,
  Upload,
  User,
  Users,
  Zap,
} from 'lucide';

window.Alpine = Alpine;
Alpine.start();

const iconSet = {
  icons: {
    Activity,
    ArrowRight,
    BrainCircuit,
    Camera,
    CirclePlay,
    Cpu,
    ExternalLink,
    Files,
    Gauge,
    History,
    ImageOff,
    Info,
    LayoutDashboard,
    Lock,
    LogIn,
    LogOut,
    Mail,
    Moon,
    Plus,
    RotateCcw,
    Send,
    ShieldCheck,
    Sparkles,
    Siren,
    Sun,
    Trash2,
    Upload,
    User,
    Users,
    Zap,
  },
};

createIcons(iconSet);

const transitionDelay = 1000;
const wait = (duration) => new Promise((resolve) => {
  window.setTimeout(resolve, duration);
});

const setLoaderVisible = (visible) => {
  const loader = document.getElementById('page-loader');

  if (!loader) {
    return;
  }

  loader.classList.toggle('hidden', !visible);
  loader.classList.toggle('flex', visible);
};

const replacePage = (html, url) => {
  const nextDocument = new DOMParser().parseFromString(html, 'text/html');
  const currentContent = document.getElementById('page-content');
  const nextContent = nextDocument.getElementById('page-content');

  if (!currentContent || !nextContent) {
    window.location.href = url;
    return;
  }

  document.title = nextDocument.title;
  currentContent.replaceChildren(...Array.from(nextContent.childNodes));
  currentContent.scrollTop = 0;

  const currentNav = document.querySelector('nav');
  const nextNav = nextDocument.querySelector('nav');
  if (currentNav && nextNav) {
    currentNav.replaceChildren(...Array.from(nextNav.childNodes));
    Alpine.initTree(currentNav);
  }

  Alpine.initTree(currentContent);
  createIcons(iconSet);
};

document.addEventListener('click', async (event) => {
  if (!document.getElementById('page-content')) {
    return;
  }

  const link = event.target.closest('a');

  if (!link || link.target || link.hasAttribute('download')) {
    return;
  }

  if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.defaultPrevented) {
    return;
  }

  const nextUrl = new URL(link.href, window.location.href);

  if (nextUrl.origin !== window.location.origin) {
    return;
  }

  if (nextUrl.pathname === window.location.pathname && nextUrl.search === window.location.search) {
    return;
  }

  event.preventDefault();
  setLoaderVisible(true);

  try {
    const [response] = await Promise.all([
      fetch(nextUrl.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      }),
      wait(transitionDelay),
    ]);

    if (!response.ok) {
      window.location.href = nextUrl.href;
      return;
    }

    replacePage(await response.text(), nextUrl.href);
    window.history.pushState({}, '', nextUrl.href);
    setLoaderVisible(false);
  } catch (error) {
    setLoaderVisible(false);
    window.location.href = nextUrl.href;
  }
}, true);

window.addEventListener('popstate', async () => {
  setLoaderVisible(true);

  try {
    const [response] = await Promise.all([
      fetch(window.location.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      }),
      wait(transitionDelay),
    ]);

    if (!response.ok) {
      window.location.reload();
      return;
    }

    replacePage(await response.text(), window.location.href);
    setLoaderVisible(false);
  } catch (error) {
    setLoaderVisible(false);
    window.location.reload();
  }
});
