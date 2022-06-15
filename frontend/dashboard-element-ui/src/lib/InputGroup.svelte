<script lang="ts">
   import { ErrorMessage } from "svelte-forms-lib";
   export let label:string;
   export let field:string;

</script>
<label class="block">
    <span>{label}</span>
    <div class="input block mt-1 ">
        <slot {field}></slot>
    </div>
</label>
<ErrorMessage class="text-red-500" name={field}></ErrorMessage>


<style>
    .input > :global(*) {
        width: 100%;
    }

</style>