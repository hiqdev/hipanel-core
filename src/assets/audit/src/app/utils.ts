import * as dayjs from "dayjs";
import type { DataType, Filters } from "./types";

export const colors: Record<string, string> = {
  "create": "green",
  "update": "geekblue",
  "delete": "volcano",
};

// the timestamp can be in seconds or in ms (13 digits)
export const toMs = (timestamp: string): number => {
  const value = parseInt(timestamp, 10);

  return value > 999999999999 ? value : value * 1000;
};

export const formatTimestamp = (timestamp: string): string =>
  isNaN(parseInt(timestamp, 10)) ? timestamp : dayjs.unix(toMs(timestamp) / 1000).format("YYYY-MM-DD HH:mm:ss");

export const shortTrace = (traceId?: string): string => (traceId?.split("-")[1] ?? traceId ?? "").slice(0, 8);

export const isEmpty = (value: any): boolean => {
  if (value === null || value === undefined) return true;
  if (Array.isArray(value)) return value.length === 0;
  if (typeof value === "object") return Object.keys(value).length === 0;

  return false;
};

// the table of the object whose history is shown (undefined for the trace page)
const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
const pageSegment = new URL(window.location.href).pathname.split("/")[2];
export const entityTable = pageSegment && !uuidRegex.test(pageSegment) ? pageSegment : undefined;
export const canSplitNested = entityTable !== undefined && entityTable !== "trace";

export const emptyFilters: Filters = { search: "", users: [], operations: [], range: null, scope: "all" };

export const matches = (row: DataType, { search, users, operations, range, scope }: Filters): boolean => {
  const needle = search.trim().toLowerCase();
  const data = row.diff.new ?? row.diff.old ?? {};
  const ts = toMs(row.timestamp);

  return (
    (!needle || [row.entity_id, row.table, data.prop_name, data.value].some((v) => String(v ?? "").toLowerCase().includes(needle)))
    && (users.length === 0 || users.includes(row.user?.login))
    && (operations.length === 0 || operations.includes(row.operation))
    && (!range || (ts >= range[0] && ts <= range[1]))
    && (scope === "all" || (scope === "direct") === (row.table === entityTable))
  );
};

// Rows with the same trace_id (one request) are put under a synthetic header row.
export const groupByTrace = (rows: DataType[]): DataType[] => {
  const byTrace = new Map<string, DataType[]>();
  rows.forEach((row) => {
    const traceId = row.request?.trace_id;
    if (traceId) {
      byTrace.set(traceId, [...(byTrace.get(traceId) ?? []), row]);
    }
  });
  const result: DataType[] = [];
  const done = new Set<string>();
  rows.forEach((row) => {
    const traceId = row.request?.trace_id;
    const members = traceId ? byTrace.get(traceId)! : [];
    if (members.length < 2) {
      result.push(row);
    } else if (!done.has(traceId!)) {
      done.add(traceId!);
      result.push({ ...members[0], key: `g:${traceId}`, kind: "group", rows: members });
      members.forEach((member) => result.push({ ...member, inGroup: true }));
    }
  });

  return result;
};
