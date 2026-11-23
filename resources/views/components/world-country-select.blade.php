@props([
    'name' => 'origin_country',
    'value' => '',
    'placeholder' => 'Pilih atau ketik nama negara...',
    'required' => true,
    'id' => 'countryPicker_' . uniqid(),
])

<div class="relative world-country-wrapper" id="{{ $id }}">
    <div class="relative flex items-center">
        <input
            type="text"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            {{ $required ? 'required' : '' }}
            class="country-search-input w-full bg-[#FFF7FA] border border-[#F8BBD0] focus:border-[#8E3B56] focus:outline-none rounded-2xl pl-4 pr-10 py-2.5 font-semibold text-[#3D1824]"
        >
        <button type="button" tabindex="-1" class="country-toggle-btn absolute right-3 text-[#8E3B56] hover:text-[#3D1824]">
            <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </button>
    </div>

    <ul class="country-dropdown-list hidden absolute z-40 left-0 right-0 mt-1.5 max-h-56 overflow-y-auto bg-white border border-[#F8BBD0] rounded-2xl shadow-lg py-1.5 text-xs"></ul>
</div>

@once
<script>
const WORLD_COUNTRIES = [
    "Afganistan","Afrika Selatan","Afrika Tengah","Albania","Aljazair","Amerika Serikat","Andorra","Angola","Antigua dan Barbuda","Arab Saudi",
    "Argentina","Armenia","Australia","Austria","Azerbaijan","Bahama","Bahrain","Bangladesh","Barbados","Belanda",
    "Belarus","Belgia","Belize","Benin","Bhutan","Bolivia","Bosnia dan Herzegovina","Botswana","Brasil","Britania Raya (Inggris)",
    "Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Ceko","Chad","Chili","China (Tiongkok)","Denmark","Djibouti",
    "Dominika","Ekuador","El Salvador","Eritrea","Estonia","Eswatini","Etiopia","Fiji","Filipina","Finlandia",
    "Gabon","Gambia","Georgia","Ghana","Grenada","Guatemala","Guinea","Guinea Khatulistiwa","Guinea-Bissau","Guyana",
    "Haiti","Honduras","Hongaria","India","Indonesia","Irak","Iran","Irlandia","Islandia","Israel",
    "Italia","Jamaika","Jepang","Jerman","Kamboja","Kamerun","Kanada","Kazakhstan","Kenya","Kirgizstan",
    "Kiribati","Kolombia","Komoro","Kongo","Korea Selatan","Korea Utara","Kosta Rika","Kroasia","Kuba","Kuwait",
    "Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lituania","Luksemburg","Madagaskar",
    "Maladewa","Malawi","Malaysia","Mali","Malta","Maroko","Marshall","Mauritania","Mauritius","Meksiko",
    "Mesir","Mikronesia","Moldova","Monako","Mongolia","Montenegro","Mozambik","Myanmar","Namibia","Nauru",
    "Nepal","Niger","Nigeria","Nikaragua","Norwegia","Oman","Pakistan","Palau","Palestina","Panama",
    "Pantai Gading","Papua Nugini","Paraguay","Peru","Polandia","Portugal","Prancis","Qatar","Republik Demokratik Kongo","Republik Dominika",
    "Rumania","Rusia","Rwanda","Saint Kitts dan Nevis","Saint Lucia","Saint Vincent dan Grenadines","Samoa","San Marino","Sao Tome dan Principe","Selandia Baru",
    "Senegal","Serbia","Seychelles","Sierra Leone","Singapura","Siprus","Slovakia","Slovenia","Solomon","Somalia",
    "Spanyol","Sri Lanka","Sudan","Sudan Selatan","Suriah","Suriname","Swedia","Swiss","Tajikistan","Tanjung Verde",
    "Tanzania","Thailand","Timor-Leste","Togo","Tonga","Trinidad dan Tobago","Tunisia","Turki","Turkmenistan","Tuvalu",
    "Uganda","Ukraina","Uni Emirat Arab","Uruguay","Uzbekistan","Vanuatu","Vatikan","Venezuela","Vietnam","Yaman",
    "Yordania","Yunani","Zambia","Zimbabwe"
];

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.world-country-wrapper').forEach(wrapper => {
        const input = wrapper.querySelector('.country-search-input');
        const list  = wrapper.querySelector('.country-dropdown-list');
        const btn   = wrapper.querySelector('.country-toggle-btn');

        function renderList(keyword = '') {
            const q = keyword.trim().toLowerCase();
            const starts = WORLD_COUNTRIES.filter(c => c.toLowerCase().startsWith(q));
            const contains = WORLD_COUNTRIES.filter(c => !c.toLowerCase().startsWith(q) && c.toLowerCase().includes(q));
            const results = q ? [...starts, ...contains] : WORLD_COUNTRIES;

            list.innerHTML = '';
            if (results.length === 0) {
                list.innerHTML = '<li class="px-4 py-2.5 text-[#8E5668] italic">Negara tidak ditemukan</li>';
            } else {
                results.forEach(country => {
                    const li = document.createElement('li');
                    li.textContent = country;
                    li.className = 'px-4 py-2 cursor-pointer font-semibold text-[#3D1824] hover:bg-[#FCE4EC] hover:text-[#8E3B56] transition';
                    li.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        input.value = country;
                        list.classList.add('hidden');
                    });
                    list.appendChild(li);
                });
            }
            list.classList.remove('hidden');
        }

        input.addEventListener('focus', () => renderList(input.value));
        input.addEventListener('input', () => renderList(input.value));
        btn.addEventListener('click', () => {
            if (list.classList.contains('hidden')) {
                input.focus();
                renderList('');
            } else {
                list.classList.add('hidden');
            }
        });
        input.addEventListener('blur', () => setTimeout(() => list.classList.add('hidden'), 150));
    });
});
</script>
@endonce