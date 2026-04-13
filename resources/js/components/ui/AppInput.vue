<script setup lang="ts">
defineProps<{
    id:           string
    label:        string
    modelValue:   string
    type?:        string
    placeholder?: string
    autocomplete?: string
    disabled?:    boolean
    error?:       string
    hint?:        string
}>()

defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()
</script>

<template>
    <div class="field">
        <!-- Label -->
        <label :for="id" class="field__label">
            {{ label }}
        </label>

        <!-- Input wrapper (con slot de icono opcional) -->
        <div class="field__wrap" :class="{ 'field__wrap--error': error }">
            <span v-if="$slots.icon" class="field__icon" aria-hidden="true">
                <slot name="icon" />
            </span>

            <input
                :id="id"
                :type="type ?? 'text'"
                :value="modelValue"
                :placeholder="placeholder"
                :autocomplete="autocomplete"
                :disabled="disabled"
                :aria-invalid="!!error"
                :aria-describedby="error ? `${id}-error` : hint ? `${id}-hint` : undefined"
                class="field__input"
                :class="{ 'field__input--with-icon': $slots.icon }"
                @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            />
        </div>

        <!-- Hint -->
        <p v-if="hint && !error" :id="`${id}-hint`" class="field__hint">
            {{ hint }}
        </p>

        <!-- Error -->
        <p v-if="error" :id="`${id}-error`" class="field__error" role="alert">
            {{ error }}
        </p>
    </div>
</template>

<style scoped>
.field {
    display:        flex;
    flex-direction: column;
    gap:            var(--space-1);
}

.field__label {
    font-size:   var(--text-xs);
    font-weight: 500;
    color:       var(--color-text);
}

/* ── Wrap ──────────────────────────────────────────────────── */
.field__wrap {
    position:      relative;
    display:       flex;
    align-items:   center;
}
.field__wrap--error .field__input {
    border-color: var(--color-error);
}
.field__wrap--error .field__input:focus {
    box-shadow: 0 0 0 3px color-mix(in oklch, var(--color-error) 15%, transparent);
}

/* ── Icon ──────────────────────────────────────────────────── */
.field__icon {
    position:       absolute;
    left:           var(--space-3);
    color:          var(--color-text-faint);
    pointer-events: none;
    display:        flex;
    align-items:    center;
}

/* ── Input ─────────────────────────────────────────────────── */
.field__input {
    width:         100%;
    height:        38px;
    padding:       0 var(--space-3);
    font-size:     var(--text-sm);
    color:         var(--color-text);
    background:    var(--color-surface);
    border:        1px solid var(--color-border);
    border-radius: var(--radius-md);
    outline:       none;
    transition:    border-color var(--transition-interactive),
    box-shadow   var(--transition-interactive);
}
.field__input--with-icon {
    padding-left: calc(var(--space-3) + 14px + var(--space-2));
}
.field__input:focus {
    border-color: var(--color-primary);
    box-shadow:   0 0 0 3px color-mix(in oklch, var(--color-primary) 15%, transparent);
}
.field__input:disabled {
    opacity:  0.5;
    cursor:   not-allowed;
    background: var(--color-surface-offset);
}
.field__input::placeholder {
    color: var(--color-text-faint);
}

/* ── Hint / Error ──────────────────────────────────────────── */
.field__hint  { font-size: var(--text-xs); color: var(--color-text-faint); }
.field__error { font-size: var(--text-xs); color: var(--color-error); }
</style>
