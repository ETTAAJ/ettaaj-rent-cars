<?php
/**
 * DATE RANGE PICKER - INTEGRATION CODE
 * 
 * This file contains the complete HTML, CSS, and JavaScript for the Date Range Picker.
 * Copy and paste the styles and scripts into your booking.php file.
 * 
 * USAGE:
 * 1. Replace the existing date inputs with the date range picker input
 * 2. Add the CSS styles to your <style> section
 * 3. Add the JavaScript before the closing </body> tag
 * 4. Initialize the picker with your minDays value
 */

// ============================================
// STEP 1: HTML - Replace your date inputs
// ============================================
/*
Replace this section in booking.php (around line 778-789):

OLD:
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="relative">
        <input type="date" name="pickup" id="pickup" required 
               class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition">
        <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['pickup_date'] ?></label>
    </div>
    <div class="relative">
        <input type="date" name="return" id="return" required 
               class="w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition">
        <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['return_date'] ?></label>
    </div>
</div>

NEW:
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="relative">
        <div class="date-range-picker-container">
            <input type="text" 
                   id="date-range-input" 
                   class="date-range-input w-full p-4 bg-white/10 border border-border rounded-2xl focus:ring-2 focus:ring-gold focus:border-gold transition" 
                   placeholder="<?= $text['pickup_date'] ?> → <?= $text['return_date'] ?>" 
                   readonly
                   required>
            <input type="hidden" name="pickup" id="pickup">
            <input type="hidden" name="return" id="return">
            <div id="date-range-picker-popup" class="date-range-picker-popup"></div>
        </div>
        <label class="absolute <?= $lang === 'ar' ? 'right-4' : 'left-4' ?> -top-2.5 bg-[var(--card)] px-3 text-xs font-bold text-gold"><?= $text['trip_dates'] ?></label>
    </div>
</div>
*/

?>

<!-- ============================================
     STEP 2: CSS - Add to your <style> section
     ============================================ -->
<style>
/* Date Range Picker Styles */
.date-range-picker-container {
    position: relative;
    width: 100%;
}

.date-range-input {
    width: 100%;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 1rem;
    color: var(--primary);
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.date-range-input:hover {
    border-color: rgba(255, 215, 0, 0.5);
    background: rgba(255, 255, 255, 0.15);
}

.date-range-input:focus {
    outline: none;
    border-color: #FFB22C;
    box-shadow: 0 0 0 3px rgba(255, 178, 44, 0.2);
}

.date-range-picker-popup {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: 0;
    z-index: 1000;
    background: var(--card-dark);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    padding: 1.5rem;
    min-width: 600px;
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.date-range-picker-popup.show {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

.date-range-picker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.date-range-picker-nav {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.date-range-picker-nav-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.3);
    border-radius: 0.5rem;
    color: #FFB22C;
    cursor: pointer;
    transition: all 0.3s ease;
}

.date-range-picker-nav-btn:hover {
    background: rgba(255, 178, 44, 0.2);
    border-color: #FFB22C;
    transform: scale(1.1);
}

.date-range-picker-nav-btn:active {
    transform: scale(0.95);
}

.date-range-picker-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.date-range-picker-month-year {
    font-size: 1.125rem;
    font-weight: 700;
    color: #FFB22C;
    min-width: 150px;
    text-align: center;
}

.date-range-picker-calendars {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.date-range-picker-calendar {
    display: flex;
    flex-direction: column;
}

.date-range-picker-calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.date-range-picker-weekday {
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    padding: 0.5rem 0;
}

.light .date-range-picker-weekday {
    color: rgba(30, 41, 59, 0.6);
}

.date-range-picker-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.25rem;
}

.date-range-picker-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    color: var(--primary);
    position: relative;
}

.date-range-picker-day:hover:not(.disabled):not(.start-date):not(.end-date) {
    background: rgba(255, 178, 44, 0.2);
    transform: scale(1.1);
}

.date-range-picker-day.disabled {
    opacity: 0.3;
    cursor: not-allowed;
    color: rgba(255, 255, 255, 0.3);
}

.light .date-range-picker-day.disabled {
    color: rgba(30, 41, 59, 0.3);
}

.date-range-picker-day.in-range {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0;
}

.light .date-range-picker-day.in-range {
    background: rgba(217, 119, 6, 0.1);
}

.date-range-picker-day.in-range:first-child {
    border-top-left-radius: 0.5rem;
    border-bottom-left-radius: 0.5rem;
}

.date-range-picker-day.in-range:last-child {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

.date-range-picker-day.start-date {
    background: #FFB22C;
    color: #000;
    font-weight: 700;
    border-radius: 0.5rem;
    z-index: 2;
}

.date-range-picker-day.end-date {
    background: #FFB22C;
    color: #000;
    font-weight: 700;
    border-radius: 0.5rem;
    z-index: 2;
}

.date-range-picker-day.start-date.in-range {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.date-range-picker-day.end-date.in-range {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.date-range-picker-day.selected {
    background: rgba(255, 178, 44, 0.3);
}

.date-range-picker-footer {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 215, 0, 0.2);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.date-range-picker-note {
    text-align: center;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.7);
    font-style: italic;
}

.light .date-range-picker-note {
    color: rgba(30, 41, 59, 0.7);
}

.date-range-picker-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.date-range-picker-btn {
    padding: 0.75rem 2rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.date-range-picker-btn-cancel {
    background: rgba(255, 255, 255, 0.1);
    color: var(--primary);
    border: 1px solid rgba(255, 215, 0, 0.3);
}

.date-range-picker-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 215, 0, 0.5);
    transform: translateY(-2px);
}

.light .date-range-picker-btn-cancel {
    background: rgba(30, 41, 59, 0.1);
    color: #1e293b;
    border-color: rgba(217, 119, 6, 0.3);
}

.date-range-picker-btn-apply {
    background: linear-gradient(135deg, #FFB22C, #FFA500);
    color: #000;
    font-weight: 700;
}

.date-range-picker-btn-apply:hover {
    background: linear-gradient(135deg, #FFA500, #FF8C00);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 178, 44, 0.4);
}

.date-range-picker-btn-apply:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .date-range-picker-popup {
        min-width: 100%;
        left: 0;
        right: 0;
        padding: 1rem;
    }

    .date-range-picker-calendars {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .date-range-picker-actions {
        flex-direction: column;
    }

    .date-range-picker-btn {
        width: 100%;
    }
}
</style>

<!-- ============================================
     STEP 3: JAVASCRIPT - Add before </body>
     ============================================ -->
<script>
// Date Range Picker Class
class DateRangePicker {
    constructor(inputId, options = {}) {
        this.input = document.getElementById(inputId);
        this.popup = document.getElementById('date-range-picker-popup');
        this.pickupInput = document.getElementById('pickup');
        this.returnInput = document.getElementById('return');
        this.minDays = options.minDays || 3;
        this.onApply = options.onApply || null;
        this.onCancel = options.onCancel || null;
        
        this.startDate = null;
        this.endDate = null;
        this.currentMonth = new Date();
        this.isOpen = false;
        
        this.init();
    }

    init() {
        this.attachEvents();
        this.updateInput();
    }

    attachEvents() {
        // Open/close on input click
        this.input.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.popup.contains(e.target) && !this.input.contains(e.target)) {
                this.close();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.isOpen = true;
        this.popup.classList.add('show');
        this.render();
    }

    close() {
        this.isOpen = false;
        this.popup.classList.remove('show');
    }

    render() {
        const month1 = new Date(this.currentMonth);
        const month2 = new Date(this.currentMonth);
        month2.setMonth(month2.getMonth() + 1);

        this.popup.innerHTML = `
            <div class="date-range-picker-header">
                <div class="date-range-picker-nav">
                    <button class="date-range-picker-nav-btn" onclick="dateRangePicker.prevMonth()" type="button">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="date-range-picker-nav-btn" onclick="dateRangePicker.nextMonth()" type="button">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="date-range-picker-calendars">
                ${this.renderCalendar(month1)}
                ${this.renderCalendar(month2)}
            </div>
            <div class="date-range-picker-footer">
                <p class="date-range-picker-note">Minimum rental period is ${this.minDays} days</p>
                <div class="date-range-picker-actions">
                    <button class="date-range-picker-btn date-range-picker-btn-cancel" onclick="dateRangePicker.handleCancel()" type="button">
                        Cancel
                    </button>
                    <button class="date-range-picker-btn date-range-picker-btn-apply" onclick="dateRangePicker.handleApply()" type="button" ${!this.isValidRange() ? 'disabled' : ''}>
                        Apply
                    </button>
                </div>
            </div>
        `;
    }

    renderCalendar(month) {
        const year = month.getFullYear();
        const monthIndex = month.getMonth();
        const monthName = month.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        
        const firstDay = new Date(year, monthIndex, 1);
        const lastDay = new Date(year, monthIndex + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        let html = `
            <div class="date-range-picker-calendar">
                <div class="date-range-picker-month-year">${monthName}</div>
                <div class="date-range-picker-calendar-header">
                    ${['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(day => 
                        `<div class="date-range-picker-weekday">${day}</div>`
                    ).join('')}
                </div>
                <div class="date-range-picker-days">
        `;

        // Empty cells for days before month starts
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += '<div class="date-range-picker-day"></div>';
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, monthIndex, day);
            const dateStr = this.formatDate(date);
            const isDisabled = this.isDateDisabled(date);
            const isStart = this.startDate && this.formatDate(this.startDate) === dateStr;
            const isEnd = this.endDate && this.formatDate(this.endDate) === dateStr;
            const inRange = this.isDateInRange(date);

            let classes = 'date-range-picker-day';
            if (isDisabled) classes += ' disabled';
            if (isStart) classes += ' start-date';
            if (isEnd) classes += ' end-date';
            if (inRange) classes += ' in-range';

            html += `
                <div class="${classes}" 
                     data-date="${dateStr}"
                     onclick="dateRangePicker.selectDate('${dateStr}')"
                     ${isDisabled ? 'style="pointer-events: none;"' : ''}>
                    ${day}
                </div>
            `;
        }

        html += '</div></div>';
        return html;
    }

    isDateDisabled(date) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const checkDate = new Date(date);
        checkDate.setHours(0, 0, 0, 0);
        
        // Disable past dates
        if (checkDate < today) {
            return true;
        }

        // If start date is selected, disable dates that would result in less than minDays
        if (this.startDate && !this.endDate) {
            const daysDiff = Math.ceil((checkDate - this.startDate) / (1000 * 60 * 60 * 24));
            if (daysDiff > 0 && daysDiff < this.minDays) {
                return true;
            }
        }

        return false;
    }

    isDateInRange(date) {
        if (!this.startDate || !this.endDate) return false;
        
        const checkDate = new Date(date);
        checkDate.setHours(0, 0, 0, 0);
        const start = new Date(this.startDate);
        start.setHours(0, 0, 0, 0);
        const end = new Date(this.endDate);
        end.setHours(0, 0, 0, 0);

        return checkDate > start && checkDate < end;
    }

    selectDate(dateStr) {
        const date = new Date(dateStr);
        
        if (!this.startDate || (this.startDate && this.endDate)) {
            // Start new selection
            this.startDate = date;
            this.endDate = null;
        } else {
            // Select end date
            const daysDiff = Math.ceil((date - this.startDate) / (1000 * 60 * 60 * 24));
            
            if (daysDiff < this.minDays) {
                // Invalid selection, reset
                this.startDate = date;
                this.endDate = null;
            } else if (date < this.startDate) {
                // Selected date is before start, make it the new start
                this.startDate = date;
                this.endDate = null;
            } else {
                // Valid end date
                this.endDate = date;
            }
        }

        this.render();
        this.updateInput();
    }

    isValidRange() {
        if (!this.startDate || !this.endDate) return false;
        const daysDiff = Math.ceil((this.endDate - this.startDate) / (1000 * 60 * 60 * 24));
        return daysDiff >= this.minDays;
    }

    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    updateInput() {
        if (this.startDate && this.endDate) {
            const startStr = this.formatDate(this.startDate);
            const endStr = this.formatDate(this.endDate);
            this.input.value = `${startStr} → ${endStr}`;
            
            // Update hidden inputs for form submission
            if (this.pickupInput) this.pickupInput.value = startStr;
            if (this.returnInput) this.returnInput.value = endStr;
        } else if (this.startDate) {
            this.input.value = this.formatDate(this.startDate);
            if (this.pickupInput) this.pickupInput.value = this.formatDate(this.startDate);
            if (this.returnInput) this.returnInput.value = '';
        } else {
            this.input.value = '';
            if (this.pickupInput) this.pickupInput.value = '';
            if (this.returnInput) this.returnInput.value = '';
        }
    }

    prevMonth() {
        this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
        this.render();
    }

    nextMonth() {
        this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
        this.render();
    }

    handleApply() {
        if (this.isValidRange()) {
            const startStr = this.formatDate(this.startDate);
            const endStr = this.formatDate(this.endDate);
            this.input.value = `${startStr} → ${endStr}`;
            
            // Update hidden inputs
            if (this.pickupInput) this.pickupInput.value = startStr;
            if (this.returnInput) this.returnInput.value = endStr;
            
            // Trigger change event for existing price calculation
            if (this.pickupInput) this.pickupInput.dispatchEvent(new Event('change'));
            if (this.returnInput) this.returnInput.dispatchEvent(new Event('change'));
            
            if (this.onApply) {
                this.onApply(this.startDate, this.endDate);
            }
            
            this.close();
        }
    }

    handleCancel() {
        // Reset to previous values or clear
        this.startDate = null;
        this.endDate = null;
        this.updateInput();
        this.close();
        
        if (this.onCancel) {
            this.onCancel();
        }
    }

    getStartDate() {
        return this.startDate ? this.formatDate(this.startDate) : null;
    }

    getEndDate() {
        return this.endDate ? this.formatDate(this.endDate) : null;
    }

    setDates(startDateStr, endDateStr) {
        if (startDateStr) {
            this.startDate = new Date(startDateStr);
        }
        if (endDateStr) {
            this.endDate = new Date(endDateStr);
        }
        this.updateInput();
        this.render();
    }
}

// Initialize the date range picker when DOM is ready
let dateRangePicker;
document.addEventListener('DOMContentLoaded', () => {
    const minDays = <?= $minDays ?? 3 ?>;
    
    dateRangePicker = new DateRangePicker('date-range-input', {
        minDays: minDays,
        onApply: (start, end) => {
            // Trigger price update if needed
            if (typeof scheduleUpdate === 'function') {
                scheduleUpdate();
            }
        },
        onCancel: () => {
            // Handle cancel if needed
        }
    });
});
</script>
