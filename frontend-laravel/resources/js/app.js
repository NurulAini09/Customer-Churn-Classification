import Alpine from 'alpinejs';
import 'flowbite';
import {
  Chart,
  ArcElement,
  LineElement,
  BarElement,
  PointElement,
  BarController,
  DoughnutController,
  LineController,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';
import {
  Activity,
  AlertCircle,
  AlertTriangle,
  ArrowLeft,
  ArrowRight,
  ArrowUpRight,
  BarChart3,
  BrainCircuit,
  Calculator,
  Calendar,
  Camera,
  Check,
  CheckCircle,
  CheckCircle2,
  ChevronLeft,
  ChevronRight,
  ChevronsUpDown,
  CirclePlay,
  Clock,
  Cpu,
  createIcons,
  DollarSign,
  Download,
  ExternalLink,
  Eye,
  Files,
  Filter,
  Flame,
  Gauge,
  Globe,
  HelpCircle,
  History,
  ImageOff,
  Info,
  Key,
  KeyRound,
  Layers,
  LayoutDashboard,
  Lock,
  LogIn,
  LogOut,
  Mail,
  MapPin,
  Menu,
  MessageSquare,
  Minus,
  Moon,
  PanelLeftClose,
  PanelLeftOpen,
  Phone,
  PhoneCall,
  PieChart,
  Plus,
  RefreshCw,
  RotateCcw,
  Search,
  Send,
  ShieldAlert,
  ShieldCheck,
  Sliders,
  Sparkles,
  Siren,
  Sun,
  Sunrise,
  Sunset,
  Trash2,
  TrendingDown,
  TrendingUp,
  Upload,
  User,
  UserPlus,
  Users,
  UserX,
  X,
  Zap,
} from 'lucide';

Chart.register(
  ArcElement,
  LineElement,
  BarElement,
  PointElement,
  BarController,
  DoughnutController,
  LineController,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Filler
);

window.Chart = Chart;
window.Alpine = Alpine;
Alpine.start();

const iconSet = {
  icons: {
    Activity,
    AlertCircle,
    AlertTriangle,
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    BarChart3,
    BrainCircuit,
    Calculator,
    Calendar,
    Camera,
    Check,
    CheckCircle,
    CheckCircle2,
    ChevronLeft,
    ChevronRight,
    ChevronsUpDown,
    CirclePlay,
    Clock,
    Cpu,
    DollarSign,
    Download,
    ExternalLink,
    Eye,
    Files,
    Filter,
    Flame,
    Gauge,
    Globe,
    HelpCircle,
    History,
    ImageOff,
    Info,
    Key,
    KeyRound,
    Layers,
    LayoutDashboard,
    Lock,
    LogIn,
    LogOut,
    Mail,
    MapPin,
    Menu,
    MessageSquare,
    Minus,
    Moon,
    PanelLeftClose,
    PanelLeftOpen,
    Phone,
    PhoneCall,
    PieChart,
    Plus,
    RefreshCw,
    RotateCcw,
    Search,
    Send,
    ShieldAlert,
    ShieldCheck,
    Sliders,
    Sparkles,
    Siren,
    Sun,
    Sunrise,
    Sunset,
    Trash2,
    TrendingDown,
    TrendingUp,
    Upload,
    User,
    UserPlus,
    Users,
    UserX,
    X,
    Zap,
  },
};

window.lucide = {
  createIcons: () => createIcons(iconSet)
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
