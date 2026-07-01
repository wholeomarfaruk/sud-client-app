// Customer Portal — Alpine store for the shared slide-out drawer.
// Livewire v3 bundles Alpine itself, so this only registers app-specific state.
document.addEventListener('alpine:init', () => {
    Alpine.store('drawer', { open: false });
});
