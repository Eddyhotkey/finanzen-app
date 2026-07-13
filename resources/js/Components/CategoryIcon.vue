<script setup>
import {
    ShoppingCartIcon,
    HomeIcon,
    TruckIcon,
    BanknotesIcon,
    GiftIcon,
    HeartIcon,
    DevicePhoneMobileIcon,
    AcademicCapIcon,
    WrenchScrewdriverIcon,
    CakeIcon,
    SunIcon,
    BoltIcon,
    UserGroupIcon,
    ComputerDesktopIcon,
    FilmIcon,
    BuildingOfficeIcon,
    QuestionMarkCircleIcon,
    ShieldCheckIcon,
    WalletIcon,
    ShoppingBagIcon,
    TvIcon,
    TrophyIcon,
    PaperAirplaneIcon,
    MusicalNoteIcon,
    ScaleIcon,
    CreditCardIcon,
    HandRaisedIcon,
    TicketIcon,
    BookOpenIcon,
    BuildingOffice2Icon,
    BuildingLibraryIcon,
    ChartBarIcon,
    SparklesIcon,
    FlagIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    icon: {
        type: String,
        default: null,
    },
});

const icons = {
    'shopping-cart': ShoppingCartIcon,
    home: HomeIcon,
    car: TruckIcon,
    banknotes: BanknotesIcon,
    gift: GiftIcon,
    health: HeartIcon,
    phone: DevicePhoneMobileIcon,
    education: AcademicCapIcon,
    repair: WrenchScrewdriverIcon,
    food: CakeIcon,
    vacation: SunIcon,
    electricity: BoltIcon,
    family: UserGroupIcon,
    computer: ComputerDesktopIcon,
    entertainment: FilmIcon,
    work: BuildingOfficeIcon,
    insurance: ShieldCheckIcon,
    savings: WalletIcon,
    clothing: ShoppingBagIcon,
    subscriptions: TvIcon,
    sport: TrophyIcon,
    travel: PaperAirplaneIcon,
    music: MusicalNoteIcon,
    taxes: ScaleIcon,
    'credit-card': CreditCardIcon,
    charity: HandRaisedIcon,
    tickets: TicketIcon,
    books: BookOpenIcon,
    rent: BuildingOffice2Icon,
    bank: BuildingLibraryIcon,
    investment: ChartBarIcon,
    care: SparklesIcon,
    goal: FlagIcon,
};

const IconComponent = icons[props.icon] ?? QuestionMarkCircleIcon;
</script>

<template>
    <component :is="IconComponent" class="h-5 w-5" />
</template>
