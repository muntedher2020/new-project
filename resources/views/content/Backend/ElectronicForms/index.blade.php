@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'الاستمارات الإلكترونية')
@section('page-title', 'إدارة الاستمارات الإلكترونية')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/nouislider/nouislider.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@vite(['resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
@vite(['resources/assets/vendor/scss/pages/front-page-landing.scss'])
@endsection

@section('content')

    @livewire('backend.electronic-forms.electronic-form')

@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/nouislider/nouislider.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
    @vite(['resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/front-page-landing.js'])
    @vite(['resources/assets/js/extended-ui-sweetalert2.js'])

    <script>
        function closeForm() {
            $('.modal-backdrop.fade.show').remove();
        }
    </script>
@endsection
{{-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Vendor Scripts -->


<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/front-page-landing.js'])
    @vite(['resources/assets/js/extended-ui-sweetalert2.js'])

    <script>
        document.addEventListener('livewire:init', () => {
            window.addEventListener('ElectronicFormModalShow', event => {
                $('#modalElectronicFormtitle').focus();
            })

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-start',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Livewire.on('show-toast', (event) => {
                const iconColors = {
                    success: 'success',
                    warning: 'warning',
                    error: 'error',
                    info: 'info'
                };

                Toast.fire({
                    icon: event.type,
                    title: `<strong>${event.title}</strong><hr>${event.message}`,
                    background: '#FFF',
                    color: '#000'
                });
                $('#addelectronicformModal').modal('hide');
                $('#editelectronicformModal').modal('hide');
                $('#removeelectronicformModal').modal('hide');
            });
        });

        // Print file function - طباعة مع معالجة خاصة للـ PDF
        function printFile(fileUrl) {
            if (!fileUrl) {
                alert('لا يوجد ملف للطباعة');
                return;
            }

            // تحديد نوع الملف
            const fileExtension = fileUrl.split('.').pop().toLowerCase();
            const isPDF = fileExtension === 'pdf';

            if (isPDF) {
                // للـ PDF فتح في نافذة جديدة مع إعطاء المستخدم التحكم الكامل
                const printWindow = window.open(
                    fileUrl,
                    '_blank',
                    'width=1000,height=700,scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes'
                );

                if (printWindow) {
                    // إعطاء المستخدم وقت لرؤية الملف قبل عرض نافذة الطباعة
                    printWindow.addEventListener('load', function() {
                        setTimeout(() => {
                            printWindow.focus();
                            // عرض نافذة الطباعة دون إغلاق النافذة تلقائياً
                            printWindow.print();
                            // السماح للمستخدم بإغلاق النافذة بنفسه
                        }, 1500);
                    });

                    // backup timeout في حالة عدم تحميل الـ load event
                    setTimeout(() => {
                        if (printWindow && !printWindow.closed) {
                            try {
                                printWindow.focus();
                                printWindow.print();
                            } catch (e) {
                                console.log('PDF print backup failed:', e);
                            }
                        }
                    }, 3000);
                } else {
                    alert('فشل في فتح نافذة الطباعة. تحقق من إعدادات النوافذ المنبثقة.');
                }
            } else {
                // للصور والملفات الأخرى - iframe مخفي
                const iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.left = '-9999px';
                iframe.style.width = '1px';
                iframe.style.height = '1px';
                iframe.src = fileUrl;

                document.body.appendChild(iframe);

                iframe.onload = function() {
                    setTimeout(() => {
                        try {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                            setTimeout(() => {
                                if (document.body.contains(iframe)) {
                                    document.body.removeChild(iframe);
                                }
                            }, 1000);
                        } catch (e) {
                            console.log('Image print failed:', e);
                            const printWindow = window.open(fileUrl, '_blank', 'width=1,height=1');
                            if (printWindow) {
                                printWindow.onload = function() {
                                    printWindow.print();
                                    printWindow.close();
                                };
                            }
                            if (document.body.contains(iframe)) {
                                document.body.removeChild(iframe);
                            }
                        }
                    }, 500);
                };

                iframe.onerror = function() {
                    console.log('Image iframe load failed');
                    const printWindow = window.open(fileUrl, '_blank', 'width=1,height=1');
                    if (printWindow) {
                        printWindow.onload = function() {
                            printWindow.print();
                            printWindow.close();
                        };
                    }
                    if (document.body.contains(iframe)) {
                        document.body.removeChild(iframe);
                    }
                };
            }
        }

        // دالة طباعة PDF - بساطة مثل زر العرض
        function printPDF(fileUrl) {
            // فتح PDF في نافذة جديدة مع خيارات طباعة محسنة
            const printWindow = window.open(
                fileUrl,
                '_blank',
                'width=1000,height=700,scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes'
            );

            if (printWindow) {
                // تركيز على النافذة الجديدة ثم عرض خيارات الطباعة
                setTimeout(() => {
                    printWindow.focus();
                    printWindow.print();
                }, 2000);
            } else {
                alert('فشل في فتح نافذة الطباعة. تحقق من إعدادات النوافذ المنبثقة.');
            }
        }

        // دالة طباعة الصور
        function printImage(fileUrl) {
            // إنشاء iframe مخفي لتحميل وطباعة الصورة
            const iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.left = '-9999px';
            iframe.style.width = '1px';
            iframe.style.height = '1px';
            iframe.src = fileUrl;

            document.body.appendChild(iframe);

            // انتظار تحميل المحتوى ثم الطباعة مباشرة
            iframe.onload = function() {
                setTimeout(() => {
                    try {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        // إزالة الـ iframe بعد الطباعة
                        setTimeout(() => {
                            if (document.body.contains(iframe)) {
                                document.body.removeChild(iframe);
                            }
                        }, 1000);
                    } catch (e) {
                        console.log('Image print failed:', e);
                        // في حالة فشل الـ iframe، استخدم النافذة المخفية
                        const printWindow = window.open(fileUrl, '_blank', 'width=1,height=1');
                        if (printWindow) {
                            printWindow.onload = function() {
                                printWindow.print();
                                printWindow.close();
                            };
                        }
                        if (document.body.contains(iframe)) {
                            document.body.removeChild(iframe);
                        }
                    }
                }, 500);
            };

            // في حالة فشل تحميل الـ iframe
            iframe.onerror = function() {
                console.log('Image iframe load failed');
                const printWindow = window.open(fileUrl, '_blank', 'width=1,height=1');
                if (printWindow) {
                    printWindow.onload = function() {
                        printWindow.print();
                        printWindow.close();
                    };
                }
                if (document.body.contains(iframe)) {
                    document.body.removeChild(iframe);
                }
            };
        }

        // Function to show file selection indicator with icon - محسنة للثبات
        function showFileSelected(input, indicatorId) {
            const indicator = document.getElementById(indicatorId);
            const fileName = document.getElementById(indicatorId.replace('fileSelected', 'fileName'));

            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Convert to MB
                const fileInfo = {
                    name: file.name,
                    size: fileSize,
                    timestamp: Date.now(),
                    inputId: input.id
                };

                // حفظ معلومات الملف في localStorage فوراً
                localStorage.setItem('fileSelected_' + indicatorId, JSON.stringify(fileInfo));

                // إظهار المؤشر فوراً
                displayFileIndicator(indicatorId, fileInfo);

                // إضافة مراقب لإعادة الإظهار عند تحديث الصفحة
                setTimeout(() => {
                    restoreFileIndicators();
                }, 100);

                // إضافة مراقب إضافي في حالة تأخر Livewire
                setTimeout(() => {
                    if (document.getElementById(indicatorId)) {
                        displayFileIndicator(indicatorId, fileInfo);
                    }
                }, 500);

            } else {
                // إزالة معلومات الملف من localStorage عند عدم اختيار ملف
                localStorage.removeItem('fileSelected_' + indicatorId);
                if (indicator) {
                    indicator.style.display = 'none';
                }
            }
        }

        // دالة منفصلة لإظهار المؤشر
        function displayFileIndicator(indicatorId, fileInfo) {
            const indicator = document.getElementById(indicatorId);
            const fileName = document.getElementById(indicatorId.replace('fileSelected', 'fileName'));

            if (fileName && fileInfo) {
                fileName.textContent = fileInfo.name + ' (' + fileInfo.size + ' MB)';
            }

            if (indicator) {
                indicator.style.display = 'block';

                // Add animation effect only if not already visible
                if (indicator.style.opacity !== '1') {
                    indicator.style.opacity = '0';
                    setTimeout(() => {
                        indicator.style.transition = 'opacity 0.3s ease-in-out';
                        indicator.style.opacity = '1';
                    }, 50);
                }
            }
        }

        // دالة استعادة حالة الملفات المحفوظة - محسنة
        function restoreFileIndicators() {
            // البحث عن جميع مؤشرات الملفات
            const indicators = document.querySelectorAll('[id^="fileSelected"]');

            indicators.forEach(indicator => {
                const indicatorId = indicator.id;

                // استرجاع معلومات الملف من localStorage
                const savedFileInfo = localStorage.getItem('fileSelected_' + indicatorId);

                if (savedFileInfo) {
                    try {
                        const fileInfo = JSON.parse(savedFileInfo);

                        // التحقق من أن المعلومات ليست قديمة (أقل من 10 دقائق)
                        const tenMinutes = 10 * 60 * 1000;
                        if (Date.now() - fileInfo.timestamp < tenMinutes) {
                            displayFileIndicator(indicatorId, fileInfo);
                        } else {
                            // إزالة المعلومات القديمة
                            localStorage.removeItem('fileSelected_' + indicatorId);
                        }
                    } catch (e) {
                        // إزالة البيانات التالفة
                        localStorage.removeItem('fileSelected_' + indicatorId);
                    }
                }
            });
        }        // دالة تنظيف مؤشرات الملفات عند إغلاق المودال
        function clearFileIndicators(modalType) {
            const indicators = document.querySelectorAll('[id*="fileSelected' + modalType + '"]');
            indicators.forEach(indicator => {
                localStorage.removeItem('fileSelected_' + indicator.id);
                indicator.style.display = 'none';
            });
        }

        // Initialize flatpickr for search fields
        document.addEventListener('livewire:load', function () {
            // Initialize flatpickr for search date inputs
            const searchDateInputs = document.querySelectorAll('.flatpickr-input');
            searchDateInputs.forEach(function(input) {
                if (!input.classList.contains('flatpickr-initialized')) {
                    let config = {
                        dateFormat: 'Y-m-d',
                        locale: 'ar',
                        allowInput: true
                    };

                    // Different config for different date types
                    if (input.classList.contains('flatpickr-datetime')) {
                        config.enableTime = true;
                        config.dateFormat = 'Y-m-d H:i:S';
                        config.time_24hr = true;
                    } else if (input.classList.contains('flatpickr-month-year')) {
                        config.placeholder = 'التاريخ';
                        config.altInput = true;
                        config.allowInput = true;
                        config.dateFormat = 'Y-m';
                        config.altFormat = 'F Y';
                        config.yearSelectorType = 'input';
                        config.locale = {
                            months: {
                                shorthand: ['كانون الثاني', 'شباط', 'آذار', 'نيسان', 'أيار', 'حزيران', 'تموز',
                                    'آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'
                                ],
                                longhand: ['كانون الثاني', 'شباط', 'آذار', 'نيسان', 'أيار', 'حزيران', 'تموز',
                                    'آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'
                                ]
                            }
                        };
                        config.plugins = [
                            new monthSelectPlugin({
                                shorthand: true,
                                dateFormat: 'Y-m',
                                altFormat: 'F Y',
                                theme: 'light'
                            })
                        ];
                    }

                    const fp = flatpickr(input, config);
                    input.classList.add('flatpickr-initialized');

                    // Sync with Livewire for search fields
                    fp.config.onChange.push(function(selectedDates, dateStr, instance) {
                        // Update the input value and trigger Livewire update
                        input.value = dateStr;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                }
            });

            // استعادة مؤشرات الملفات المرفوعة
            restoreFileIndicators();
        });

        // استعادة مؤشرات الملفات بعد تحديثات Livewire
        document.addEventListener('livewire:updated', function () {
            setTimeout(() => {
                restoreFileIndicators();
            }, 100);
        });

        // إضافة مراقب DOM للتأكد من ثبات الأيقونات
        if (window.MutationObserver) {
            const observer = new MutationObserver(function(mutations) {
                let shouldRestore = false;
                mutations.forEach(function(mutation) {
                    // التحقق من إضافة أو إزالة عقد تحتوي على file input
                    if (mutation.type === 'childList') {
                        const addedNodes = Array.from(mutation.addedNodes);
                        const hasFileInput = addedNodes.some(node => {
                            return node.nodeType === 1 &&
                                   (node.querySelector &&
                                    node.querySelector('[id*="fileSelected"]'));
                        });
                        if (hasFileInput) {
                            shouldRestore = true;
                        }
                    }
                });

                if (shouldRestore) {
                    setTimeout(() => {
                        restoreFileIndicators();
                    }, 200);
                }
            });

            // مراقبة تغييرات في body
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    </script>
@endsection --}}