/**
 * Visitor Tracking Script
 * Collects cookies, form data (name, email, phone), and page information
 */

(function() {
    'use strict';
    
    // Configuration
    const TRACK_ENDPOINT = 'track_visitor.php';
    const TRACK_INTERVAL = 300000; // Track every 5 minutes (reduced frequency)
    const TRACK_ON_FORM_SUBMIT = true;
    const TRACK_ON_PAGE_VIEW = false; // Don't track on every page view - only when there's data
    
    // Get or create session ID
    function getSessionId() {
        let sessionId = localStorage.getItem('visitor_session_id');
        if (!sessionId) {
            sessionId = 'visitor_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('visitor_session_id', sessionId);
        }
        return sessionId;
    }
    
    // Storage for collected data
    let visitorData = {
        session_id: getSessionId(),
        name: null,
        email: null,
        phone: null,
        cookies: {},
        page_url: window.location.href,
        referrer: document.referrer || null,
        form_submit: false
    };
    
    // Track if we've already sent initial tracking
    let hasTrackedInitial = false;
    
    /**
     * Get all cookies as an object
     */
    function getAllCookies() {
        const cookies = {};
        if (document.cookie && document.cookie !== '') {
            const split = document.cookie.split(';');
            for (let i = 0; i < split.length; i++) {
                const nameValue = split[i].split('=');
                const name = nameValue[0].trim();
                const value = nameValue[1] ? decodeURIComponent(nameValue[1].trim()) : '';
                cookies[name] = value;
            }
        }
        return cookies;
    }
    
    /**
     * Extract email from text
     */
    function extractEmail(text) {
        if (!text) return null;
        const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g;
        const matches = text.match(emailRegex);
        return matches ? matches[0] : null;
    }
    
    /**
     * Extract phone from text
     */
    function extractPhone(text) {
        if (!text) return null;
        // Match various phone formats
        const phoneRegex = /[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}/g;
        const matches = text.match(phoneRegex);
        if (matches) {
            // Return the longest match (most likely to be complete)
            return matches.sort((a, b) => b.length - a.length)[0].trim();
        }
        return null;
    }
    
    /**
     * Extract name from input field
     */
    function extractName(input) {
        if (!input || !input.value) return null;
        const value = input.value.trim();
        // Check if it looks like a name (not email, not phone, has letters)
        if (value && !value.includes('@') && !/^\d+$/.test(value) && /[a-zA-Z]/.test(value)) {
            return value;
        }
        return null;
    }
    
    /**
     * Monitor form inputs for name, email, phone
     */
    function monitorFormInputs() {
        // Monitor all input fields
        document.addEventListener('input', function(e) {
            const input = e.target;
            if (!input || input.tagName !== 'INPUT') return;
            
            const type = (input.type || '').toLowerCase();
            const name = (input.name || '').toLowerCase();
            const id = (input.id || '').toLowerCase();
            const value = input.value.trim();
            
            // Check for email
            if (type === 'email' || name.includes('email') || id.includes('email')) {
                if (value) {
                    visitorData.email = extractEmail(value) || value;
                }
            }
            
            // Check for phone
            if (type === 'tel' || name.includes('phone') || id.includes('phone') || name.includes('whatsapp')) {
                if (value) {
                    visitorData.phone = extractPhone(value) || value.replace(/\D/g, '');
                }
            }
            
            // Check for name
            if (type === 'text' && (name.includes('name') || id.includes('name') || name.includes('fullname'))) {
                if (value) {
                    visitorData.name = extractName(input) || value;
                }
            }
        }, true);
        
        // Monitor form submissions
        if (TRACK_ON_FORM_SUBMIT) {
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form || form.tagName !== 'FORM') return;
                
                // Extract data from form
                const formData = new FormData(form);
                
                // Try to get name, email, phone from form
                for (let [key, value] of formData.entries()) {
                    const lowerKey = key.toLowerCase();
                    if (lowerKey.includes('name') && !visitorData.name) {
                        visitorData.name = value.trim();
                    } else if (lowerKey.includes('email') && !visitorData.email) {
                        visitorData.email = extractEmail(value) || value.trim();
                    } else if ((lowerKey.includes('phone') || lowerKey.includes('tel')) && !visitorData.phone) {
                        visitorData.phone = extractPhone(value) || value.trim();
                    }
                }
                
                // Mark as form submit
                visitorData.form_submit = true;
                
                // Track immediately on form submit
                trackVisitor();
            }, true);
        }
    }
    
    /**
     * Send tracking data to server
     */
    function trackVisitor(force = false) {
        // Update cookies and page URL
        visitorData.cookies = getAllCookies();
        visitorData.page_url = window.location.href;
        visitorData.referrer = document.referrer || null;
        
        // Only send if we have meaningful data OR if forced (form submit)
        const hasData = visitorData.email || visitorData.phone || visitorData.name;
        if (!hasData && !force && !visitorData.form_submit) {
            return;
        }
        
        // Send to server
        fetch(TRACK_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(visitorData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset form_submit flag after successful tracking
                if (visitorData.form_submit) {
                    visitorData.form_submit = false;
                }
            }
        })
        .catch(error => {
            console.error('Tracking error:', error);
        });
    }
    
    /**
     * Initialize tracking
     */
    function initTracking() {
        // Don't track on initial page view - only track when there's actual data
        // This prevents duplicate records for visitors who don't fill forms
        
        // Monitor form inputs
        monitorFormInputs();
        
        // Periodic tracking (every 5 minutes) - only if there's data
        setInterval(function() {
            // Only track periodically if we have meaningful data
            if (visitorData.email || visitorData.phone || visitorData.name) {
                trackVisitor();
            }
        }, TRACK_INTERVAL);
        
        // Track on page unload only if there's meaningful data
        window.addEventListener('beforeunload', function() {
            if (visitorData.email || visitorData.phone || visitorData.name) {
                // Use sendBeacon for reliable tracking on page unload
                visitorData.cookies = getAllCookies();
                visitorData.page_url = window.location.href;
                const data = JSON.stringify(visitorData);
                // sendBeacon requires Blob or FormData
                const blob = new Blob([data], { type: 'application/json' });
                navigator.sendBeacon(TRACK_ENDPOINT, blob);
            }
        });
    }
    
    // Start tracking when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTracking);
    } else {
        initTracking();
    }
    
})();

