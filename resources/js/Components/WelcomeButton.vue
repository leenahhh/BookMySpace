<template>
    <button
        class="group inline-flex px-6 py-3 border-2 border-white text-white text-lg rounded-full transition hover:bg-white hover:text-purple-700"
    >
        <!-- Icon on the Left (if provided) -->
        <div
            v-if="icon && iconPlacement === 'left'"
            class="w-0 pr-0 opacity-0 translate-x-[-100%] transition-all duration-200 group-hover:w-5 group-hover:translate-x-0 group-hover:mr-2 group-hover:-ml-1 group-hover:opacity-100"
        >
            <slot name="icon" />
        </div>

        <!-- Button Text -->
        <span>
            <slot />
        </span>

        <!-- Icon on the Right (if provided) -->
        <div
            v-if="icon && iconPlacement === 'right'"
            class="w-0 pl-0 opacity-0 translate-x-[100%] transition-all duration-200 group-hover:w-5 group-hover:translate-x-0 group-hover:pl-2 group-hover:opacity-100"
        >
            <slot name="icon" />
        </div>
    </button>
</template>

<script>
    export default {
        name: "WelcomeButton",
        props: {
                icon: {
                type: Boolean,
                default: false,
            },
            iconPlacement: {
                type: String,
                default: "right", // Default placement of the icon: "left" or "right"
                validator: (value) => ["left", "right"].includes(value),
            },
        },
    };
</script>
