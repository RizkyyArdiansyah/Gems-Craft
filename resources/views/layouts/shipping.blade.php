<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Shipping</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Awal halaman dalam keadaan transparan dan sedikit kecil */
        .fade-enter {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        /* Saat halaman dimuat, kembali ke normal */
        .fade-enter-focus {
            opacity: 1;
            transform: scale(1) translateY(0);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
    </style>



</head>

<body x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
    <div class="p-4 w-full">
        <div class="container mx-auto mt-5 rounded-md bg-slate-100 select-none">
            <div class="flex flex-row justify-between items-center select-none">
                <h1 class="text-xl font-bold ml-4 py-2">Shipping</h1>
                @include('components/icons.progress', ['currentStep' => 'Shipping'])
                <div
                    class="font-semibold text-[0.6rem] md:text-xs mt-3 bg-white w-32 md:w-28 rounded-md hover:bg-blue-400 hover:text-slate-50 p-2 mr-4 ">
                    <a href="/cart">
                        <— Back to Cart</a>
                </div>
            </div>

            <div x-bind:class="{
                {{-- 'opacity-0 translate-x-full': !
                    show,
                'opacity-100 translate-x-0 transition-all duration-700 ease-out': show --}}
            }" class="p-4 ">
                <div class="flex flex-col items-center justify-center md:items-start md:flex-row gap-x-2 gap-y-2">
                    <!-- Container Kiri: Summary Cart -->
                    <div class="w-full bg-white p-4 rounded-md shadow md:w-[43%]">
                        <div class="mb-4">
                            <h2 class="text-xs md:text-md font-semibold">Summary Cart</h2>
                            <p class="text-[0.5rem]">You have {{ count($cartItems) }} items in your cart.</p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center justify-between text-xs border-b pb-2">
                                    <!-- Gambar Produk -->
                                    <img src="{{ asset('storage/' . $item->product->images) }}" alt="Product Image" class="w-12 h-8 rounded">

                                    <!-- Nama Produk -->
                                    <span
                                        class="w-[55%] ml-3 text-ellipsis capitalize text-[0.7rem]">{{ $item->product->product_name }}</span>

                                    <span class="text-[0.7rem] md:text-[0.6rem] text-center w-8 md:w-10">x {{ $item->quantity }} </span>
                                    
                                    <!-- Total Harga Produk -->
                                    <span class="font-semibold text-[0.6rem] w-20 text-end">Rp
                                        {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Keseluruhan -->
                        <div class="flex flex-row justify-between text-xs py-3">
                            <div class="flex flex-col gap-y-2 font-semibold w-1/2">
                                <p>Subtotal</p>
                                <p>Shipping Cost (+)</p>
                                <p>Discount (-)</p>
                            </div>
                            <div class="flex flex-col gap-y-1.5 font-semibold w-1/2 text-end text-[0.65rem] ">
                                <div class="">
                                    Rp
                                    {{ number_format($cartTotal, 0, ',', '.') }}
                                </div>
                                <div id="cost-display" class="text-tight">
                                    @if(session('shipping.cost'))
                                        <span class="font-semibold">Rp {{ number_format(session('shipping.cost'), 0, '.', '.') }}</span>
                                    @else
                                        <span class="font-semibold mr-4">-</span>
                                    @endif
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>


                    <!-- Container Kanan: Form Shipping -->
                    <div class="w-full bg-white p-4 rounded-md shadow select-none">
                        <h2 class="text-xs md:text-md font-bold">Your Personal Detail</h2>
                    
                        <div class="flex flex-row gap-x-1.5 md:gap-x-8 mt-3 gap-y-2">
                            <div class="flex w-[49%] md:w-1/2 flex-col gap-y-2">
                                <label for="name" class="font-semibold text-xs">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ session('shipping.name', '') }}"
                                    placeholder="Masukkan nama lengkap" required
                                    class="border border-gray-700 focus:border-blue-500 h-7 text-xs p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div class="flex w-[49%] md:w-1/2 flex-col gap-y-2">
                                <label for="email" class="font-semibold text-xs">Email</label>
                                <input type="email" id="email" name="email" value="{{ session('shipping.email', '') }}"
                                    placeholder="Masukkan email" required
                                    class="border border-gray-700 focus:border-blue-500 h-7 text-xs p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>
                    
                        <div class="flex flex-col gap-y-2 mt-2 text-xs w-full md:w-[48%]">
                            <label for="phone" class="font-semibold text-xs">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" value="{{ session('shipping.phone', '') }}" required
                                class="border border-gray-700 focus:border-blue-500 h-7 text-xs p-2 rounded-md w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    
                        <div class="flex flex-col my-4">
                            <h2 class="text-xs md:text-md font-bold">Shipping Address</h2>
                    
                            <div class="flex flex-row gap-x-2 md:gap-x-8 mt-3 gap-y-2">
                                <div class="w-1/2 flex flex-col gap-y-2">
                                    <label class="block text-xs font-semibold">Province</label>
                                    <select id="province" name="province" required
                                        class="border border-gray-700 focus:border-blue-500 text-xs p-2">
                                        <option value="">Choose Province</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province['province_id'] }}"
                                                {{ session('shipping.province') == $province['province_id'] ? 'selected' : '' }}>
                                                {{ $province['province'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <div class="flex w-1/2 flex-col gap-y-2">
                                    <label class="block text-xs font-semibold">City</label>
                                    <select id="city" name="city" required
                                        class="border border-gray-700 focus:border-blue-500 text-xs p-2">
                                        <option value="">Choose City</option>
                                        @if (session('shipping.city'))
                                            <option value="{{ session('shipping.city') }}" selected>
                                                {{ session('shipping.city_name') }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                    
                            <div class="flex flex-row gap-x-2 md:gap-x-8 mt-3 gap-y-2">
                                <div class="flex w-[50%] md:w-[48%] flex-col gap-y-2">
                                    <label class="block text-xs font-semibold">Courier</label>
                                    <select id="courier" name="courier" required
                                        class="border border-gray-700 focus:border-blue-500 text-xs p-2">
                                        <option value="">Choose Courier</option>
                                        <option value="jne" {{ session('shipping.courier') == 'jne' ? 'selected' : '' }}>JNE</option>
                                        <option value="tiki" {{ session('shipping.courier') == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                        <option value="pos" {{ session('shipping.courier') == 'pos' ? 'selected' : '' }}>POS</option>
                                    </select>
                                </div>
                                <div class="flex w-[48%] flex-col gap-y-2">
                                    <label class="block text-xs font-semibold">Service</label>
                                    <select id="service" name="service" required
                                        class="border border-gray-700 focus:border-blue-500 text-xs p-2" disabled>
                                        <option value="">Choose Service</option>
                                    </select>
                                </div>
                            </div>
                    
                            <div class="flex w-full flex-col gap-y-2 mt-3">
                                <label for="address" class="font-semibold text-xs">Full Address</label>
                                <textarea id="address" name="address" placeholder="Enter your full address" required
                                    class="text-sm border p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none" rows="3">{{ session('shipping.address', '') }}</textarea>
                            </div>
                    
                            <div class="flex mt-3 justify-center items-center">
                                <button type="submit" id="payment-btn"
                                    class="bg-blue-500 hover:bg-blue-700 hover:font-semibold text-white px-3 py-2 rounded disabled:opacity-50" disabled>
                                    Continue to Payment
                                </button>
                            </div>
                
                    

                            <script>
                                var input = document.querySelector("#phone");
                                var iti = window.intlTelInput(input, {
                                    initialCountry: "id",
                                    separateDialCode: true,
                                    preferredCountries: ["id", "us", "gb"],
                                });

                                // Saat submit, format nomornya biar tetap +62
                                input.addEventListener("blur", function() {
                                    if (iti.isValidNumber()) {
                                        input.value = iti.getNumber(); // Format full international
                                    }
                                });
                            </script>



                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const paymentBtn = document.getElementById("payment-btn");
                                const nameInput = document.getElementById("name");
                                const emailInput = document.getElementById("email");
                                const phoneInput = document.getElementById("phone");
                                const provinceSelect = document.getElementById("province");
                                const citySelect = document.getElementById("city");
                                const courierSelect = document.getElementById("courier");
                                const serviceSelect = document.getElementById("service");
                                const addressInput = document.getElementById("address");
                                const costDisplay = document.getElementById("cost-display");
                                
                                // Set button disabled at start
                                paymentBtn.disabled = true;

                                // Function to check if all inputs are filled
                                function validateForm() {
                                    // Check if all required fields have values
                                    const nameValid = nameInput.value.trim() !== "";
                                    const emailValid = emailInput.value.trim() !== "";
                                    const phoneValid = phoneInput.value.trim() !== "";
                                    const provinceValid = provinceSelect.value !== "";
                                    const cityValid = citySelect.value !== "";
                                    const courierValid = courierSelect.value !== "";
                                    const serviceValid = serviceSelect.value !== "";
                                    const addressValid = addressInput.value.trim() !== "";
                                    
                                    // Enable button only if all fields are valid
                                    paymentBtn.disabled = !(nameValid && emailValid && phoneValid && 
                                                           provinceValid && cityValid && courierValid && 
                                                           serviceValid && addressValid);
                                }

                                // Add input event listeners to all form fields
                                nameInput.addEventListener("input", validateForm);
                                emailInput.addEventListener("input", validateForm);
                                phoneInput.addEventListener("input", validateForm);
                                provinceSelect.addEventListener("change", validateForm);
                                citySelect.addEventListener("change", validateForm);
                                courierSelect.addEventListener("change", validateForm);
                                serviceSelect.addEventListener("change", validateForm);
                                addressInput.addEventListener("input", validateForm);

                                paymentBtn.addEventListener("click", function() {
                                    if (!paymentBtn.disabled) {
                                        window.location.href = "payment";
                                    }
                                });

                                provinceSelect.addEventListener("change", function() {
                                    fetch("{{ route('shipping.getCities') }}", {
                                            method: "POST",
                                            headers: {
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify({
                                                province_id: this.value
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            citySelect.innerHTML = "<option value=''>Choose City</option>";
                                            data.forEach(city => {
                                                citySelect.innerHTML +=
                                                    `<option value="${city.city_id}">${city.city_name}</option>`;
                                            });
                                            // Revalidate form after city options are updated
                                            validateForm();
                                        });
                                });

                                courierSelect.addEventListener("change", function() {
                                    let city = citySelect.value;
                                    let courier = this.value;

                                    if (!city || !courier) {
                                        Swal.fire("Error", "Pilih kota tujuan dan kurir terlebih dahulu.", "error");
                                        return;
                                    }

                                    fetch("{{ route('shipping.calculate') }}", {
                                            method: "POST",
                                            headers: {
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify({
                                                destination: city,
                                                weight: 1000,
                                                courier: courier
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            serviceSelect.innerHTML = "<option value=''>Choose Service</option>";
                                            serviceSelect.setAttribute("data-costs", "[]");

                                            if (Array.isArray(data) && data.length > 0) {
                                                let allServices = [];

                                                data.forEach(service => {
                                                    service.costs.forEach(cost => {
                                                        let costData = {
                                                            service: cost.service,
                                                            cost: cost.cost[0].value,
                                                            etd: cost.cost[0].etd
                                                        };
                                                        allServices.push(costData);

                                                        serviceSelect.innerHTML +=
                                                            `<option value="${cost.service}">${cost.service} (Estimasi: ${cost.cost[0].etd} hari)</option>`;
                                                    });
                                                });

                                                serviceSelect.setAttribute("data-costs", JSON.stringify(allServices));
                                                serviceSelect.removeAttribute("disabled");
                                                // Revalidate form after service options are updated
                                                validateForm();
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Error fetching ongkir:", error);
                                            Swal.fire("Error", "Terjadi kesalahan saat mengambil data ongkir.", "error");
                                        });
                                });

                                serviceSelect.addEventListener("change", function() {
                                    let selectedService = this.value;
                                    let dataAttribute = serviceSelect.getAttribute("data-costs");
                                    let servicesData = dataAttribute ? JSON.parse(dataAttribute) : [];

                                    if (!selectedService || servicesData.length === 0) {
                                        costDisplay.innerText = "Rp -";
                                        return;
                                    }

                                    let selectedServiceData = servicesData.find(service => service.service === selectedService);

                                    if (!selectedServiceData) {
                                        console.log("❌ Service tidak ditemukan dalam daftar!");
                                        costDisplay.innerText = "Rp -";
                                        return;
                                    }

                                    costDisplay.innerText = "Rp " + selectedServiceData.cost.toLocaleString("id-ID");
                                    // Revalidate form after cost is updated
                                    validateForm();
                                });
                                
                                paymentBtn.addEventListener("click", function() {
                                    if (!paymentBtn.disabled) {
                                        let shippingCost = costDisplay.innerText.replace("Rp ", "").replace(/\./g,
                                            ""); // Ambil angka saja
                                        let name = document.getElementById("name").value;
                                        let email = document.getElementById("email").value;
                                        let phone = document.getElementById("phone").value;
                                        let province = document.getElementById("province").value;
                                        let city = document.getElementById("city").value;
                                        let courier = document.getElementById("courier").value;
                                        let service = document.getElementById("service").value;
                                        let address = document.getElementById("address").value;

                                        fetch("{{ route('shipping.storeCost') }}", {
                                            method: "POST",
                                            headers: {
                                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify({
                                                shipping_cost: shippingCost,
                                                name: name,
                                                email: email,
                                                phone: phone,
                                                province: province,
                                                city: city,
                                                courier: courier,
                                                service: service,
                                                address: address,

                                            })
                                        }).then(response => {
                                            if (response.ok) {
                                                console.log("Shipping data saved!");
                                                window.location.href = "{{ route('payment.index') }}";
                                            }
                                        });
                                    }
                                });
                                
                                // Run initial validation
                                validateForm();
                            });
                        </script>
                    </div>                                                                                                                                                                                                                                              
                </div>
            </div>

</body>

</html>