// Initialize on an input element, e.g., <input type="text" class="datepicker">
flatpickr(".datepicker", {
    dateFormat: "d/m/Y",  // Customize to match your old format (dmy)
    locale: {  // Croatian localization example
        firstDayOfWeek: 1,
        weekdays: {
            shorthand: ["Ne", "Po", "Ut", "Sr", "Če", "Pe", "Su"],
            longhand: ["Nedjelja", "Ponedjeljak", "Utorak", "Srijeda", "Četvrtak", "Petak", "Subota"]
        },
        months: {
            shorthand: ["Sij", "Velj", "Ožu", "Tra", "Svi", "Lip", "Srp", "Kol", "Ruj", "Lis", "Stu", "Pro"],
            longhand: ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"]
        }
    },
    onClose: function (selectedDates, dateStr, instance) {
        // Custom validation or callback if needed
    }
});