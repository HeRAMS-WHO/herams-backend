<script lang="ts">
    import { createEventDispatcher } from 'svelte';

    const dispatch = createEventDispatcher();

    export let variables = [];
    export let name ="variablepicker";

    export let valueOptions;
    
    export let value = [];

    $: {
        valueOptions = variables.filter(variable => value.includes(variable.name)).flatMap(variable => Object.keys(variable.valueOptions));
    }
 </script>
<svelte:options tag={null}/>
<select multiple {name} bind:value={value}>
    {#each variables as variable}
        {#if variable.valueOptions}
        <option value={variable.name}>{variable.label}</option>    
        {/if}
    
    {/each}
</select>
<style>
</style>
  