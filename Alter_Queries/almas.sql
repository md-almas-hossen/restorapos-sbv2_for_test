-- Queries on 13-08-2024 --

UPDATE `language` SET `english` = 'Return Sales (Pending)', `spanish` = 'Ventas Devueltas (Pendientes)' WHERE `language`.`id` = 2924

UPDATE `language` SET `english` = 'Return Sales (Setteled)', `spanish` = 'Ventas Devueltas (Liquidadas)' WHERE `language`.`id` = 2727

UPDATE `language` SET `english` = 'Tables Sales Report', `spanish` = 'Informe de Ventas por Mesas' WHERE `language`.`id` = 1495

UPDATE `language` SET `english` = 'Item Wise Report', `spanish` = 'Informe por Artículo' WHERE `language`.`id` = 1240

UPDATE `language` SET `english` = 'Add-ons Sales Report', `spanish` = 'Informe de Ventas de Complementos' WHERE `language`.`id` = 2302

UPDATE `language` SET `english` = 'Details Sales (Cashier)', `spanish` = 'Detalles de Ventas (Cajero)' WHERE `language`.`id` = 1243

UPDATE `language` SET `english` = 'Sales Commission', `spanish` = 'Comisión de Ventas' WHERE `language`.`id` = 1494

UPDATE `language` SET `english` = 'Third-Party Commission', `spanish` = 'Translation: Comisión de Terceros' WHERE `language`.`id` = 2718

UPDATE `language` SET `english` = 'App Setting', `spanish` = 'Configuración de la Aplicación' WHERE `language`.`id` = 971


-- Queries on 14-08-2024 --

UPDATE `language` SET `spanish` = 'Enviar a compra', `arabic` = 'إرسال للشراء' WHERE `language`.`phrase` = 'send_to_purchase'

UPDATE `language` SET `english` = 'Appears in date range', `spanish` = 'Aparece en el rango de fechas', `arabic` = 'يظهر في نطاق التاريخ' WHERE `language`.`phrase` = 'appears_in_date_range'

UPDATE `language` SET `spanish` = 'Cantidad total de salida de stock en el rango de fechas', `arabic` = 'إجمالي كمية المخزون الخارج في نطاق التاريخ' WHERE `language`.`phrase` = 'Total_Amount_of_stock_out_in_date_range'

UPDATE `language` SET `spanish` = 'Usado, Desperdicio, Caducado', `arabic` = 'مستخدم، هدر، منتهي الصلاحية' WHERE `language`.`phrase` = 'used_wastage_expired'

UPDATE `language` SET `spanish` = 'Cantidad total de stock disminuida de todas las cocinas en el rango de fechas', `arabic` = 'إجمالي الكمية التي انخفضت من جميع المطابخ خلال النطاق الزمني' WHERE `language`.`phrase` = 'total_amount_of_stock_decreased_from_all_kitchens_in_date_range'

UPDATE `language` SET `spanish` = 'Lista de cocinas y sus existencias en el rango de fechas', `arabic` = 'قائمة المطابخ ومخزونها خلال النطاق الزمني' WHERE `language`.`phrase` = 'list_of_kitchens_and_their_stocks_in_date_range'

UPDATE `language` SET `spanish` = 'Marcado en verde', `arabic` = 'العلامة الخضراء' WHERE `language`.`phrase` = 'the_green_marked'

UPDATE `language` SET `spanish` = 'Muestra el stock real restante en tu sistema', `arabic` = 'يظهر المخزون الفعلي المتبقي في نظامك' WHERE `language`.`phrase` = 'shows_the_actual_remaining_stock_in_your_system'

UPDATE `language` SET `spanish` = 'Añadir stock de apertura múltiple', `arabic` = 'أضف مخزون الافتتاح المتعدد' WHERE `language`.`phrase` = 'add_opening_stock_multiple'

UPDATE `language` SET `spanish` = 'Tasa', `arabic` = 'معدل' WHERE `language`.`phrase` = 'rate_open_stock'

UPDATE `language` SET `english` = 'Add Variant', `spanish` = 'Agregar Variante', `arabic` = 'أضف نسخة' WHERE `language`.`phrase` = 'add_variant'

UPDATE `language` SET `english` = 'Table No', `spanish` = 'Número de Mesa', `arabic` = 'رقم الطاولة' WHERE `language`.`phrase` = 'table_no'

UPDATE `language` SET `english` = 'Time Data', `spanish` = 'Datos de Tiempo', `arabic` = 'بيانات الوقت' WHERE `language`.`phrase` = 'time_data'


-- Queries on 31-08-2024 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `bengali`) VALUES (NULL, 'full_invoice_return', 'Full Invoice Return', 'Devolución de Factura Completa', 'إرجاع الفاتورة بالكامل', NULL)





-- Queries on 26-01-2026 --
UPDATE `language` SET `arabic` = 'اختر لون زر التمرير' WHERE `language`.`phrase` = 'select_qr_button_hover_color'
UPDATE `language` SET `arabic` = 'اختر لون الزر' WHERE `language`.`phrase` = 'select_qr_button_color'
UPDATE `language` SET `arabic` = 'قائمة ترتيب المستحقات من الطرف الثالث' WHERE `language`.`phrase` = 'thirdparty_due_order_list'
UPDATE `language` SET `arabic` = 'معدل' WHERE `language`.`phrase` = 'ingredient_rate'
UPDATE `language` SET `arabic` = 'الإعدادات' WHERE `language`.`phrase` = 'settings'
UPDATE `language` SET `arabic` = 'الإنتاجات' WHERE `language`.`phrase` = 'productions'
UPDATE `language` SET `arabic` = 'الموارد البشرية' WHERE `language`.`phrase` = 'human_resources'
UPDATE `language` SET `arabic` = 'تقرير المشتريات' WHERE `language`.`phrase` = 'purchases_report'
UPDATE `language` SET `arabic` = 'لوحة تحكم التقارير' WHERE `language`.`phrase` = 'report_dashboard'
UPDATE `language` SET `arabic` = 'عرض المطبخ (KDS)' WHERE `language`.`phrase` = 'kitchen_dislpay'
UPDATE `language` SET `arabic` = 'نقطة البيع (POS)' WHERE `language`.`phrase` = 'point_of_sale'
UPDATE `language` SET `arabic` = 'ملاحظة: سيتم مسح جميع بيانات السجلات بعد تشغيل إعادة تعيين السجلات.' WHERE `language`.`phrase` = 'logs_reset_note'
UPDATE `language` SET `arabic` = 'إعادة تعيين السجلات' WHERE `language`.`phrase` = 'logs_reset'
UPDATE `language` SET `arabic` = 'بيسا' WHERE `language`.`phrase` = 'paisa'
UPDATE `language` SET `arabic` = 'تكا' WHERE `language`.`taka` = 'taka'
UPDATE `language` SET `arabic` = 'حسابك غير نشط. يرجى الاتصال بالدعم.' WHERE `language`.`phrase` = 'your_account_is_inactive_please_contact_support'
UPDATE `language` SET `arabic` = 'طلبات الشراء' WHERE `language`.`phrase` = 'purchase_orders'
UPDATE `language` SET `arabic` = 'تم تحويل طلب الشراء بالفعل' WHERE `language`.`phrase` = 'purchase_order_already_converted'
UPDATE `language` SET `arabic` = 'تم دمج السجل الفرعي' WHERE `language`.`phrase` = 'sub_ledger_merged'
UPDATE `language` SET `arabic` = 'اختر البنك' WHERE `language`.`phrase` = 'select_bank'
UPDATE `language` SET `arabic` = 'دفع بواسطة البطاقة' WHERE `language`.`phrase` = 'card_payment'
UPDATE `language` SET `arabic` = 'فشل الدفع' WHERE `language`.`phrase` = 'payment_failed'
UPDATE `language` SET `english` = 'Order Created Successfully But Payment Failed!', `arabic` = 'تم إنشاء الطلب بنجاح لكن فشل الدفع!' WHERE `language`.`phrase` = 'order_created_payment_failed'
UPDATE `language` SET `arabic` = 'بطاقة' WHERE `language`.`phrase` = 'card'
UPDATE `language` SET `arabic` = 'نقدًا' WHERE `language`.`phrase` = 'cash'
UPDATE `language` SET `arabic` = 'تم تغيير وضع الطلب بنجاح' WHERE `language`.`phrase` = 'order_mode_change_successfully'
UPDATE `language` SET `arabic` = 'تغيير وضع الطلب' WHERE `language`.`phrase` = 'order_mode_change'
UPDATE `language` SET `arabic` = 'الوضع السريع' WHERE `language`.`phrase` = 'quick_mode'
UPDATE `language` SET `arabic` = 'الوضع العادي' WHERE `language`.`phrase` = 'regular_mode'
UPDATE `language` SET `arabic` = 'وضع الطلب' WHERE `language`.`phrase` = 'order_mode'
UPDATE `language` SET `arabic` = 'إعداد وضع الطلب' WHERE `language`.`phrase` = 'order_mode_setting'
UPDATE `language` SET `arabic` = 'الدفع السريع' WHERE `language`.`phrase` = 'quick_checkout'
UPDATE `language` SET `arabic` = 'تمكين العضوية' WHERE `language`.`phrase` = 'membershipenable'
UPDATE `language` SET `arabic` = 'إعداد كلمة المرور' WHERE `language`.`phrase` = 'password_setting'
UPDATE `language` SET `arabic` = 'التحقق من كلمة المرور' WHERE `language`.`phrase` = 'verify_password'
UPDATE `language` SET `arabic` = 'أدخل كلمة المرور' WHERE `language`.`phrase` = 'type_the_password'
UPDATE `language` SET `arabic` = 'كلمة المرور مطلوبة' WHERE `language`.`phrase` = 'password_required'
UPDATE `language` SET `arabic` = 'إعادة تعيين كلمة المرور' WHERE `language`.`phrase` = 'password_reset'
UPDATE `language` SET `arabic` = 'حدث خطأ ما' WHERE `language`.`phrase` = 'something_went_wrong'
UPDATE `language` SET `arabic` = 'تم إعادة تعيين كلمة المرور بنجاح.' WHERE `language`.`phrase` = 'password_reset_successfully'
UPDATE `language` SET `arabic` = 'تم تعيين كلمة المرور بنجاح.' WHERE `language`.`phrase` = 'password_set_successfully'
UPDATE `language` SET `arabic` = 'إعداد التحقق من كلمة مرور الطلب' WHERE `language`.`phrase` = 'order_password_verification_setting'
UPDATE `language` SET `arabic` = 'تحديث كلمة المرور' WHERE `language`.`phrase` = 'password_update'
UPDATE `language` SET `arabic` = 'تم تحديث كلمة المرور بنجاح.' WHERE `language`.`phrase` = 'password_updated_successfully'
UPDATE `language` SET `arabic` = 'تحديث إعدادات كلمة مرور الطلب' WHERE `language`.`phrase` = 'update_order_password_setting'
UPDATE `language` SET `arabic` = 'خلفية التذييل' WHERE `language`.`phrase` = 'footer_bg'
UPDATE `language` SET `arabic` = 'رمز اللون بالهيكس' WHERE `language`.`phrase` = 'color_hex_code'
UPDATE `language` SET `arabic` = 'خلفية الرأس' WHERE `language`.`phrase` = 'header_bg'
UPDATE `language` SET `arabic` = 'لون الزر' WHERE `language`.`phrase` = 'button_color'
UPDATE `language` SET `arabic` = 'لون النص' WHERE `language`.`phrase` = 'text_color'
UPDATE `language` SET `arabic` = 'إعدادات اللون' WHERE `language`.`phrase` = 'color_setting'
UPDATE `language` SET `arabic` = 'السجل الفرعي' WHERE `language`.`phrase` = 'sub_ledger'
UPDATE `language` SET `arabic` = 'COA تعني \"جدول الحسابات' WHERE `language`.`phrase` = 'coa'
UPDATE `language` SET `arabic` = 'تحرير القوالب المعرفة مسبقًا' WHERE `language`.`phrase` = 'edit_predefined'
UPDATE `language` SET `arabic` = 'إنشاء قوالب معرفة مسبقًا' WHERE `language`.`phrase` = 'create_predefined'
UPDATE `language` SET `arabic` = 'اسم القالب المعرفة مسبقًا' WHERE `language`.`phrase` = 'predefined_name'
UPDATE `language` SET `arabic` = 'تمت الموافقة على جميع الفحوصات' WHERE `language`.`phrase` = 'approved_all_check'
UPDATE `language` SET `arabic` = 'تحقق من الكل' WHERE `language`.`phrase` = 'check_all'
UPDATE `language` SET `arabic` = 'تحرير القسيمة' WHERE `language`.`phrase` = 'edit_voucher'
UPDATE `language` SET `arabic` = 'تعليق السجل' WHERE `language`.`phrase` = 'ledger_comment'
UPDATE `language` SET `arabic` = 'إنشاء قسيمة' WHERE `language`.`phrase` = 'create_voucher'
UPDATE `language` SET `arabic` = 'القسائم' WHERE `language`.`phrase` = 'vouchers'
UPDATE `language` SET `arabic` = 'معدل' WHERE `language`.`phrase` = 'rate_'
UPDATE `language` SET `arabic` = 'نحن ندعم' WHERE `language`.`phrase` = 'we_support'
UPDATE `language` SET `arabic` = 'معرف العميل' WHERE `language`.`phrase` = 'client_id'
UPDATE `language` SET `arabic` = 'معدل' WHERE `language`.`phrase` = 'rate_open_stock'
UPDATE `language` SET `arabic` = 'إضافة رصيد افتتاحي متعدد' WHERE `language`.`phrase` = 'add_opening_stock_multiple'
UPDATE `language` SET `arabic` = 'يعرض المخزون المتبقي الفعلي في نظامك' WHERE `language`.`phrase` = 'shows_the_actual_remaining_stock_in_your_system'
UPDATE `language` SET `arabic` = 'المعلم باللون الأخضر' WHERE `language`.`phrase` = 'the_green_marked'
UPDATE `language` SET `arabic` = 'قائمة بالمطابخ و مخزوناتها في نطاق التاريخ' WHERE `language`.`phrase` = 'list_of_kitchens_and_their_stocks_in_date_range'
UPDATE `language` SET `arabic` = 'إجمالي كمية المخزون التي تم تقليصها من جميع المطابخ في نطاق التاريخ' WHERE `language`.`phrase` = 'total_amount_of_stock_decreased_from_all_kitchens_in_date_range'
UPDATE `language` SET `arabic` = 'المستخدم، الفاقد، منتهية الصلاحية' WHERE `language`.`phrase` = 'used_wastage_expired'
UPDATE `language` SET `arabic` = 'إجمالي كمية المخزون الخارج في نطاق التاريخ' WHERE `language`.`phrase` = 'Total_Amount_of_stock_out_in_date_range'
UPDATE `language` SET `arabic` = 'يظهر في نطاق التاريخ' WHERE `language`.`phrase` = 'appears_in_date_range'
UPDATE `language` SET `arabic` = 'إرسال للشراء' WHERE `language`.`phrase` = 'send_to_purchase'
UPDATE `language` SET `arabic` = 'اختر لون نص الزر' WHERE `language`.`phrase` = 'select_qr_button_text_color'










